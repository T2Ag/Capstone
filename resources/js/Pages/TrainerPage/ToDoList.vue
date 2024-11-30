<template>

<TrainerLayout>
   <div>
      <button @click=goBack() class="inline-flex items-center hover:text-gray-700 px-4 pt-3">
         <i class="bi bi-arrow-left text-lg mr-2"></i>
      </button>

      <div class="px-4 py-3">
         <p class="text-[30px] text-gray-600">To do Lists of {{ client.first_name }} {{ client.last_name }}</p>
      </div>
      <div class="px-4">

         <div v-if="success" class="px-2 py-1 bg-green-200 rounded border-1 border-green-500 ">
            <SuccessMessages :success="success" class="p-3"/>
         </div>
         
         <TrainerPageToDoListTable 
            :todos="todos" 
            :client="client"
         />
      </div>
   </div>
</TrainerLayout>
   
</template>
<script setup>
import TrainerLayout from '../../Layouts/TrainerLayout.vue';
import TrainerPageToDoListTable from '../../Components/TrainerPage/TrainerPageToDoListTable.vue';
import SuccessMessages from '../../Components/SuccessMessages.vue';

const props = defineProps({
   client: Object,
   todos: Array,
   success: String
})

function goBack() {
   window.history.back();
}
</script>