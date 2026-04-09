<template>
  <PageHeader :title="`To-do`">
    <PageHeaderAction url="utility/todos/" name="CustomTodo" :title="`To-do`">
      <BaseButton design="primary" @click="router.push({ name: 'StudentCustomTodoCreate' })">
        Add Task
      </BaseButton>
      <BaseButton design="white" @click="toggleArchived" class="ml-2">
        {{ showArchived ? 'Hide Archived' : 'Show Archived Only' }}
      </BaseButton>
    </PageHeaderAction>
  </PageHeader>

  <ListItem :init-url="initUrl" :pre-requisites="false" @setItems="setItems">
    <!-- Board Scrollable Area -->
    <div ref="scrollContainer" class="px-2 overflow-x-auto">
      <div class="flex space-x-4 w-max">
        
        <div v-for="(column, columnIndex) in columns" :key="column.id"
          class="w-96 rounded-lg flex-shrink-0 flex-col min-h-[300px] relative overflow-hidden" :class="{
            'bg-gradient-to-br from-blue-100 to-white border border-blue-200': column.id === 'new',
            'bg-gradient-to-b from-orange-50 to-transparent border-2 border-orange-400': column.id === 'inProgress',
            'bg-gradient-to-b from-blue-100 to-transparent border-2 border-[#951adb]': column.id === 'reviewing',
            'bg-gradient-to-b from-green-100 to-transparent border-2 border-green-500': column.id === 'completed',
            'dark:bg-neutral-800': true
          }">
          <div class="text-white shadow-md px-4 py-3" :class="{
            'bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 border-b border-blue-700 dark:border-blue-500': column.id === 'new',
            'bg-gradient-to-r from-orange-400 to-orange-600 border-b border-orange-500': column.id === 'inProgress',
            'bg-gradient-to-r from-[#b232fc] via-[#951adb] to-[#7a15b8] border-b border-[#6a1299]': column.id === 'reviewing',
            'bg-gradient-to-r from-green-600 to-green-800 border-b border-green-700': column.id === 'completed'
          }">
            <h3 class="font-semibold text-[17px] text-white">
              {{ column.name }}
            </h3>
          </div>
          <div class="p-4 space-y-2 min-h-[500px] h-full" :class="{
            'bg-gradient-to-br from-blue-100/50 to-white': column.id === 'new',
            'bg-gradient-to-br from-orange-50/50 to-transparent': column.id === 'inProgress',
            'bg-gradient-to-br from-blue-100/50 to-transparent': column.id === 'reviewing',
            'bg-gradient-to-br from-green-100/50 to-transparent': column.id === 'completed'
          }">
            <draggable v-model="column.todoDatas" :group="{ name: 'todoDatas', pull: true, put: true }" item-key="uuid"
              @change="onDragEnd($event, column.id)" class="space-y-2 overflow-y-auto h-full" :disabled="showArchived"
              :emptyInsertThreshold="50" :forceFallback="false" style="min-height: 400px; max-height: 70vh;">
              <template #item="{ element }">
                <div
                  class="bg-white rounded-lg p-3 shadow-sm border hover:shadow-md transition-shadow dark:bg-neutral-700"
                  :class="{
                    'border-green-500': element.completedAt,
                    'border-red-500': element.isOverdue,
                    'border-yellow-500': element.isDueToday,
                    'border-blue-200': !element.completedAt && !element.isOverdue && !element.isDueToday
                  }">
                  <div class="flex justify-between">
                    <div>
                      <p class="font-medium text-gray-900 dark:text-white cursor-pointer"
                        @click="router.push({ name: 'UtilityTodoShow', params: { uuid: element.uuid } })">
                        {{ element.title }}
                      </p>
                      <p class="text-[13px] text-gray-500">
                        {{ element.createdAt.formatted.formatted }} - {{ element.dueDate.formatted.formatted }}
                      </p>
                    </div>
                  </div>
                  <div class="mt-2 text-sm space-y-1 flex justify-between">
                    <span class="text-[12px] text-gray-500 mt-1"></span>
                    <span>
                      <button v-if="!showArchived"
                        v-tooltip="$trans('utility.todo.mark_as_' + (element.completedAt ? 'incomplete' : 'complete'))"
                        @click="markCompleted(element, column.id)">
                        <i class="fa-solid fa-circle-check text-green-500 mx-2 text-[12px]"></i>
                      </button>
                      <button v-if="!showArchived" v-tooltip="$trans('general.archive')"
                        @click="archive(element, column.id)">
                        <i class="fa-solid fa-box-archive text-gray-500 mx-2 text-[12px]"></i>
                      </button>
                      <button v-if="showArchived" v-tooltip="$trans('general.unarchive')"
                        @click="unarchive(element, column.id)">
                        <i class="fa-solid fa-box-open text-gray-500 mx-2 text-[12px]"></i>
                      </button>
                      <button v-tooltip="$trans('general.delete')" class="text-red-500"
                        @click="removeTask(element, column.id)">
                        <i class="fa-solid fa-trash text-red-500 mx-2 text-[12px]"></i>
                      </button>
                    </span>
                  </div>
                </div>
              </template>
            </draggable>
          </div>
        </div>
      </div>
    </div>
  </ListItem>
</template>

<script setup>
import { ref, reactive } from 'vue';
import draggable from 'vuedraggable';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
import { useToast } from 'vue-toastification';
import { confirmDelete } from "@core/helpers/alert"

const route = useRoute();
const router = useRouter();
const store = useStore();
const toast = useToast();

const initUrl = "utility/todo/";
const scrollContainer = ref(null);
const todos = reactive({ data: [] });
const showArchived = ref(false);

const columns = ref([
  { id: 'new', name: 'To-do', todoDatas: [] },
  { id: 'inProgress', name: 'In Progress', todoDatas: [] },
  { id: 'reviewing', name: 'Reviewing', todoDatas: [] },
  { id: 'completed', name: 'Completed', todoDatas: [] },
]);

const setItems = (data) => {
  console.log('setItems data:', data);
  const todos = data.data || data || [];
  columns.value.forEach(col => col.todoDatas = []);
  todos.forEach(todo => {
    const columnId = todo.column && columns.value.some(col => col.id === todo.column) ? todo.column : 'new';
    const column = columns.value.find(col => col.id === columnId);
    if (column) {
      column.todoDatas.push(todo);
    }
  });
  Object.assign(todos, { data: todos });
};

const scrollLeft = () => {
  if (scrollContainer.value) scrollContainer.value.scrollLeft -= 300;
};

const scrollRight = () => {
  if (scrollContainer.value) scrollContainer.value.scrollLeft += 300;
};

const toggleArchived = async () => {
  showArchived.value = !showArchived.value;
  await refreshTodos();
};

const onDragEnd = async (event, toColumnId) => {
  const originalColumns = JSON.parse(JSON.stringify(columns.value));

  if (event.added) {
    const todo = event.added.element;
    const itemUuids = columns.value.find(col => col.id === toColumnId).todoDatas.map(item => item.uuid);

    try {
      await store.dispatch('utility/todo/moveList', {
        uuid: todo.uuid,
        list_uuid: toColumnId,
        item_uuids: itemUuids,
      });
    } catch (error) {
      const message = error.response?.data?.message || 'Failed to move task';
      toast.error(message);
      columns.value = originalColumns;
      return;
    }
  } else if (event.moved) {
    const column = columns.value.find(col => col.id === toColumnId);
    const itemUuids = column.todoDatas.map(item => item.uuid);

    try {
      await store.dispatch('utility/todo/reorderItems', { uuids: itemUuids });
    } catch (error) {
      const message = error.response?.data?.message || 'Failed to reorder tasks';
      toast.error(message);
      columns.value = originalColumns;
      return;
    }
  }

  await refreshTodos();
};

const markCompleted = async (task, columnId) => {
  const originalColumns = JSON.parse(JSON.stringify(columns.value));
  const column = columns.value.find(col => col.id === columnId);
  const taskIndex = column.todoDatas.findIndex(t => t.uuid === task.uuid);
  if (taskIndex === -1) return;

  const newCompletedAt = task.completedAt ? null : new Date().toISOString();
  column.todoDatas[taskIndex].completedAt = newCompletedAt;
  try {
    await store.dispatch('utility/todo/status', { uuid: task.uuid });
  } catch (error) {
    const message = error.response?.data?.message || 'Failed to update task status';
    toast.error(message);
    columns.value = originalColumns;
    return;
  }

  await refreshTodos();
};

const archive = async (task, columnId) => {
  const originalColumns = JSON.parse(JSON.stringify(columns.value));
  const column = columns.value.find(col => col.id === columnId);
  const taskIndex = column.todoDatas.findIndex(t => t.uuid === task.uuid);
  if (taskIndex === -1) return;

  try {
    const response = await store.dispatch('utility/todo/archive', { uuid: task.uuid });
    column.todoDatas.splice(taskIndex, 1);
    toast.success(response.message || `Archived ${task.title}`);
    await refreshTodos();
  } catch (error) {
    const message = error.response?.data?.message || 'Failed to archive task';
    toast.error(message);
    columns.value = originalColumns;
  }
};

const unarchive = async (task, columnId) => {
  const originalColumns = JSON.parse(JSON.stringify(columns.value));
  const column = columns.value.find(col => col.id === columnId);
  const taskIndex = column.todoDatas.findIndex(t => t.uuid === task.uuid);
  if (taskIndex === -1) return;

  try {
    const response = await store.dispatch('utility/todo/unarchive', { uuid: task.uuid });
    column.todoDatas.splice(taskIndex, 1);
    toast.success(response.message || `Unarchived ${task.title}`);
    await refreshTodos();
  } catch (error) {
    const message = error.response?.data?.message || 'Failed to unarchive task';
    toast.error(message);
    columns.value = originalColumns;
  }
};

const removeTask = async (task, columnId) => {
  if (!(await confirmDelete("Are you sure you want to delete this task?"))) {
    return;
  }

  const originalColumns = JSON.parse(JSON.stringify(columns.value));
  const column = columns.value.find(col => col.id === columnId);
  const taskIndex = column.todoDatas.findIndex(t => t.uuid === task.uuid);
  if (taskIndex === -1) return;

  column.todoDatas.splice(taskIndex, 1);

  try {
    await store.dispatch('utility/todo/delete', { uuid: task.uuid });
    toast.success(`Deleted ${task.title}`);
  } catch (error) {
    const message = error.response?.data?.message || 'Failed to delete task';
    toast.error(message);
    columns.value = originalColumns;
    return;
  }

  await refreshTodos();
};

const refreshTodos = async () => {
  try {
    const response = await store.dispatch('utility/todo/list', { archived: showArchived.value });
    console.log('refreshTodos response:', response);
    setItems(response.data);
  } catch (error) {
    console.error('refreshTodos error:', error.response || error);
    const message = error.response?.data?.message || 'Failed to refresh tasks';
    toast.error(message);
  }
};
</script>

<style scoped>
.draggable-container {
  min-height: 100px;
}

.scroll-button {
  background-color: #e5e7eb;
  color: #4b5563;
  padding: 0.25rem 0.75rem;
  border-radius: 0.25rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.scroll-button:hover {
  background-color: #d1d5db;
}
</style>