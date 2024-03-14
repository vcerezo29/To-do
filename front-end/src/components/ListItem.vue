<template>
    <li class="p-2 bg-slate-200 rounded-2xl mb-2" ref="currentItem">
        <div class="grid grid-cols-4">
            <div class="col-span-3 p-2">
                <div class="flex justify-start gap-5 my-auto">
                    <Button  class="rounded w-10 h-10 hover:cursor-default"
                        :class="props.item.status === 1 ? 'text-green-400' : 'text-black'">
                        <FontAwesomeIcon :icon="faCheckCircle" />
                    </Button>
                    <div class="text-3xl" :class="props.item.status === 1? 'line-through' : ''">
                        {{ props.item.description }}
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap justify-end gap-5 p-2 relative">
                <Button @click="handleMarkImportant(props.item.id)" class="w-10 h-10 hover:bg-slate-500 hover:text-gray-50 "
                    :class="props.item.isImportant === 1 ? 'text-yellow-400' : 'text-black'">
                    <FontAwesomeIcon :icon="faStar" />
                </Button>
                <button class="w-10 h-10 mr-5 hover:bg-slate-500 rounded hover:text-gray-50" @click="toggleMenu">
                    <FontAwesomeIcon :icon="faEllipsisV" />
                    <transition enter-active-class="transition duration-300 ease-out transform"
                        enter-class="-translate-y-3 scale-95 opacity-0"
                        enter-to-class="translate-y-0 scale-100 opacity-100"
                        leave-active-class="transition duration-150 ease-in transform"
                        leave-class="translate-y-0 opacity-100" leave-to-class="-translate-y-3 opacity-0">
                        <div v-show="isVisible" class="absolute z-20" :class="{ 'top-full': menuAtBottom, 'bottom-full': !menuAtBottom, 'right-0': !menuAtLeft, 'left-0': menuAtLeft }"
                        :style="{ marginTop: menuAtBottom ? '-2rem' : '0' }">
                            <div class="relative py-1 bg-white border border-gray-200 rounded-md shadow-xl">
                                <div
                                    class="absolute top-0 w-4 h-4 origin-center transform rotate-45 -right-2 -mt-1 bg-white border-t border-l border-gray-200 rounded-sm pointer-events-none">
                                </div>
                                <div class="relative ">
                                    <button @click="handleEdit(props.item.id)"
                                        class="block w-full px-4 py-2 font-medium text-gray-700 whitespace-no-wrap hover:bg-gray-100 focus:outline-none hover:text-gray-900 focus:text-gray-900 focus:shadow-outline transition duration-300 ease-in-out">
                                        Edit Task</button>
                                    <button @click="handleMarkComplete(props.item.id)"  :disabled="props.item.status === 1"
                                        :class="props.item.status === 1? 'disabled:cursor-not-allowed':''"    
                                    class="block w-full px-4 py-2 font-medium hover:cursor-pointer text-gray-700 whitespace-no-wrap hover:bg-gray-100 focus:outline-none hover:text-gray-900 focus:text-gray-900 focus:shadow-outline transition duration-300 ease-in-out">
                                        Mark as Complete</button>
                                    <button @click="handleMarkImportant(props.item.id)"
                                        class="block w-full px-4 py-2 font-medium text-gray-700 whitespace-no-wrap hover:bg-gray-100 focus:outline-none hover:text-gray-900 focus:text-gray-900 focus:shadow-outline transition duration-300 ease-in-out">
                                        {{ props.item.isImportant === 0? 'Mark as Important' : 'Mark as Unimportant'}}</button>
                                    <button @click="handleDelete('delete',props.item.id )"
                                        class="block w-full px-4 py-2 font-medium text-gray-700 whitespace-no-wrap hover:bg-gray-100 focus:outline-none hover:text-gray-900 focus:text-gray-900 focus:shadow-outline transition duration-300 ease-in-out">
                                        Delete Task</button>
                                </div>
                            </div>
                        </div>
                    </transition>
                </button>
            </div>
        </div>
    </li>
</template>

<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faStar, faCheckCircle } from '@fortawesome/free-regular-svg-icons';
import { faEllipsisV } from '@fortawesome/free-solid-svg-icons'
import { onMounted, ref } from 'vue';

const menuAtBottom = ref(false);
const menuAtLeft = ref(false);
const isVisible = ref(false);
const currentItem = ref(null);

const props = defineProps({
    item: Object,
    handleMarkComplete: function () {},
    handleMarkImportant: function () {},
    handleDelete: function () {},
    handleEdit: function () {}
});



const toggleMenu = () => {
    isVisible.value = !isVisible.value;
};

const handleOutsideClick = (event) => {
    if (currentItem.value && !currentItem.value.contains(event.target)) {
    isVisible.value = false;
  }
};

const handleScroll = () => {
    if (isVisible.value) {
    isVisible.value = false;
  }
};

onMounted(()=>{
    window.addEventListener('click', handleOutsideClick);
    window.addEventListener('scroll', handleScroll);
})


</script>