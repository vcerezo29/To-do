<template>
  <transition enter-active-class="transition duration-300 ease-out transform"
    enter-class="-translate-y-3 scale-95 opacity-0" enter-to-class="translate-y-0 scale-100 opacity-100"
    leave-active-class="transition duration-150 ease-in transform" leave-class="translate-y-0 opacity-100"
    leave-to-class="-translate-y-3 opacity-0">
    <div v-show="props.isOpen" class="fixed inset-0 px-2 z-10 overflow-hidden flex items-center justify-center">
      <div
        class="bg-white rounded-md shadow-xl overflow-hidden max-w-md w-full sm:w-96 md:w-1/2 lg:w-2/3 xl:w-1/3 z-50">
        <div class="bg-indigo-500 text-white px-4 py-2 flex justify-between">
          <h2 v-if="props.type === 'delete'" class="text-lg font-semibold">Are you sure you want to procede?</h2>
          <h2 v-else-if="props.type === 'addSuccess'" class="text-lg font-semibold">Successfully Added</h2>
          <h2 v-else-if="props.type === 'addError'" class="text-lg font-semibold">Failed </h2>
          <h2 v-else-if="props.type === 'markCompletedSuccess'" class="text-lg font-semibold">Completed</h2>
          <h2 v-else-if="props.type === 'markCompletedError'" class="text-lg font-semibold">Failed in Completing Task</h2>
          <h2 v-else-if="props.type === 'markImportantSuccess'" class="text-lg font-semibold">Added to Important Task</h2>
          <h2 v-else-if="props.type === 'markImportantError'" class="text-lg font-semibold">Failed mark the task</h2>
          <h2 v-else-if="props.type === 'markUnimportantSuccess'" class="text-lg font-semibold">Remove from Important Task</h2>
          <h2 v-else-if="props.type === 'markUnimportantError'" class="text-lg font-semibold">Failed to remove task from Important Task</h2>
          <h2 v-else-if="props.type === 'deleteSuccess'" class="text-lg font-semibold">Task Successfully Deleted</h2>
          <h2 v-else-if="props.type === 'deleteError'" class="text-lg font-semibold">Unable to delete this task</h2>
          <h2 v-else-if="props.type === 'edit'" class="text-lg font-semibold">Editing</h2>
          <h2 v-else-if="props.type === 'editSuccess'" class="text-lg font-semibold">Task was Successfully Changed</h2>
        </div>
        <div class="p-4">
          <p v-if="props.type === 'delete'">"Are you sure you want to delete this task?</p>
          <div v-else-if="props.type === 'edit'"> 
            <div class="mt-2.5">
              <input type="text" v-model="changedTask" placeholder="Enter a new description"
                class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
            </div>
          </div>   
          <p v-else>{{props.message}}</p>
        </div>
        <div class="border-t px-4 py-2 flex justify-end gap-2">
          <button @click="props.toggleModal" class="px-3 py-1 bg-indigo-500 text-white  rounded-md w-full sm:w-auto"> Close
          </button>
          <button v-if="props.type === 'delete'" @click="props.handleDelete(props.targetTask)" class="px-3 py-1 bg-indigo-500 text-white  rounded-md w-full sm:w-auto"> Accept
          </button>
          <button v-else-if="props.type === 'edit'" @click="props.handleEdit(props.targetTaskToEdit, changedTask)" class="px-3 py-1 bg-indigo-500 text-white  rounded-md w-full sm:w-auto">Submit
          </button>
          <button @click="props.toggleModal"  v-else class="px-3 py-1 bg-indigo-500 text-white  rounded-md w-full sm:w-auto"> Okay
          </button>
        </div>
      </div>
    </div>
  </transition>

</template>
<script setup>
import { ref } from "vue";

const changedTask = ref('');

const props = defineProps({
  toggleModal: function () {},
  handleDelete: function () {},
  handleEdit: function () {},
  targetTaskToEdit: function (){},
  targetTask: Number,
  isOpen: Boolean,
  type: String,
  message: String
})
</script>