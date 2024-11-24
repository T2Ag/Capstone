<template>

<TrainerLayout>
   <div>
      <button @click=goBack() class="inline-flex items-center hover:text-gray-700 px-4 pt-3">
         <i class="bi bi-arrow-left text-lg mr-2"></i>
      </button>

      <div class="px-4 pt-3">
         <p class="text-[30px] text-gray-600">To do Lists of {{ client.first_name }} {{ client.last_name }}</p>
      </div>
      <div class="px-4">
         
         <ToDoListTable 
            :todos="todos" 
            :client="client"
         />
      </div>
   </div>
</TrainerLayout>
   
</template>
<script setup>
import TrainerLayout from '../../Layouts/TrainerLayout.vue';
import ToDoListTable from '../../Components/ToDoListTable.vue';

const props = defineProps({
   client: Object,
   todos: Array
})

function goBack() {
   window.history.back();
}
</script>