<script setup>
import ListItem from './components/ListItem.vue';
import Modal from './components/Modal.vue';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const new_task = ref("");
const todos = ref([]);
const important = ref([]);

const handleAddNewTask = () => {
  axios.post('http://127.0.0.1:8000/api/todo', {
    description: new_task.value
  }).then((response) => {
    new_task.value = "";
    todos.value.push(response.data.task)
    console.log(response.data.task);
    message.value = response.data.message;
    toggleModal('addSuccess')
  }).catch((error) => {
    new_task.value = "";
    toggleModal('addError')
    message.value = error.response.data.message;
  });
}

const handleGetAllTodos = () => {
  axios.get('http://127.0.0.1:8000/api/todo').then((response) => {
    const todosArray = Object.keys(response.data).map(key => response.data[key]);

    console.log(todosArray);
    if (todosArray.length == 1) {
      if(todosArray[0][0].isImportant === 1){
        important.value = todosArray[0];
      }else {
        todos.value = todosArray[0];
      }
      
    } else {
      todos.value = todosArray[0];
      important.value = todosArray[1];
    }
    console.log(response);
  }).catch((error) => {
    message.value = error.response.data.message;
  });
}

const handleMarkComplete = (id) => {
  axios.get('http://127.0.0.1:8000/api/todo/completed/' + id).then((response) => {
    if(response.data.task.isImportant === 1){
      const targetIndex = important.value.findIndex(obj => obj.id === id);
      important.value.splice(targetIndex, 1);
      important.value.push(response.data.task);
    }else {
      const targetIndex = todos.value.findIndex(obj => obj.id === id);
      todos.value.splice(targetIndex, 1);
      todos.value.push(response.data.task);
    }

    toggleModal('markCompletedSuccess')
    message.value = response.data.message;
  }).catch((error) => {
    new_task.value = "";
    toggleModal('markCompletedError')
    message.value = error.response.data.message;
  });
}

const handleMarkImportant = (id) => {
  var target = todos.value.filter(obj => obj.id === id);
  if(target.length  === 0){
    target = important.value.filter(obj => obj.id === id);
  }

  if (target[0].isImportant === 1) {
    axios.get('http://127.0.0.1:8000/api/todo/unimportant/' + id).then((response) => {

      const targetIndex = important.value.findIndex(obj => obj.id === id);
      important.value.splice(targetIndex, 1);
      todos.value.push(response.data.task);

      toggleModal('markUnimportantSuccess')
      message.value = response.data.message;

    }).catch((error) => {
      toggleModal('markUnimportantError')
      message.value = error.response.data.message;
    });
  } else {
    axios.get('http://127.0.0.1:8000/api/todo/important/' + id).then((response) => {

      const targetIndex = todos.value.findIndex(obj => obj.id === id);
      todos.value.splice(targetIndex, 1);
      important.value.push(response.data.task);

      toggleModal('markImportantSuccess')
      message.value = response.data.message;

    }).catch((error) => {
      toggleModal('markImportantError')
      message.value = error.response.data.message;
    });
  }
}

const handleDelete = (id) => {
  axios.delete('http://127.0.0.1:8000/api/todo/'+id).then((response)=> {
    
    var targetIndex = important.value.findIndex(obj => obj.id === id);
    console.log(targetIndex)
    if(targetIndex === null || targetIndex < 0)
    {
      targetIndex = todos.value.findIndex(obj => obj.id === id);
      todos.value.splice(targetIndex, 1);
    }else {
      important.value.splice(targetIndex, 1);
    }


    isOpen.value = false;
    toggleModal('deleteSuccess')
    message.value = response.data.message;
  }).catch((error)=>{
    toggleModal('deleteError')
    message.value = error.response.data.message;
    console.log(error.message);
  })
}

const handleEdit = (id) => {
  toggleModal('edit')
  targetTaskToEdit.value = id
}

const handleSubmitEdit = (id, task) => {
  axios.post('http://127.0.0.1:8000/api/todo/'+id, {
    description: task
  }).then((response)=> {

    if(response.data.task.isImportant === 1){
      const targetIndex = important.value.findIndex(obj => obj.id === id);
      important.value.splice(targetIndex, 1);
      important.value.push(response.data.task);
    }else {
      const targetIndex = todos.value.findIndex(obj => obj.id === id);
      todos.value.splice(targetIndex, 1);
      todos.value.push(response.data.task);
    }

    isOpen.value = false;
    toggleModal('editSuccess')
    message.value = response.data.message;
  }).then((error)=> {
    toggleModal('editError')
    message.value = error.response.data.message;
  })

  
}


const isOpen = ref(false);
const modalType = ref('');
const message = ref('');
const targetTaskTodelete = ref(null);
const targetTaskToEdit = ref(null);

const toggleModal = (type, id) => {
  if(type === 'delete'){
    targetTaskTodelete.value = id
  }
  modalType.value = type; 
  isOpen.value = !isOpen.value;
}


onMounted(() => {
  handleGetAllTodos();
})

</script>

<template>
  <div class="w-screen h-screen overflow-y-auto">
    <div class="w-11/12 h-5/6 mx-auto my-12 p-4">
      <h1 class="text-4xl text-center">To do App</h1>
      <p class="text-2xl text-center">by Vincent Cerezo</p>
      <div class="flex gap-2 mt-10">
        <div class="flex-1 w-64">
          <div class="mt-2.5">
            <input type="text" v-model="new_task" placeholder="Enter a new task"
              class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
          </div>
        </div>
        <div class="w-32 ">
          <div class="mt-2.5">
            <button @click="handleAddNewTask"
              class="block w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add
              New Task</button>
          </div>

        </div>
      </div>
      <div class=" w-full h-auto p-5">
        <h1 class="text-3xl mb-5">Important</h1>
        <div class="h-auto">
          <ul>
            <ListItem v-for="(important, index) in important" :key="index" :item="important"
              :handleMarkComplete="handleMarkComplete" :handleMarkImportant="handleMarkImportant" :handleDelete="toggleModal" :handleEdit="handleEdit"/>
          </ul>
        </div>
      </div>
      <div class="w-full p-5 h-auto">
        <h1 class="text-3xl mb-5">Tasks</h1>
        <div class="h-auto">
          <ul>
            <ListItem v-for="(todo, index) in todos" :key="index" :item="todo" :handleMarkComplete="handleMarkComplete" :handleDelete="toggleModal"
              :handleMarkImportant="handleMarkImportant" :handleEdit="handleEdit" />
          </ul>
        </div>
      </div>
    </div>
  </div>

  <Modal :toggleModal="toggleModal" :isOpen="isOpen" :type="modalType" :message="message" :handleDelete="handleDelete" :targetTask="targetTaskTodelete" :targetTaskToEdit="targetTaskToEdit" :handleEdit="handleSubmitEdit"/>
</template>
