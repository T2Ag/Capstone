<template>

<UserLayout>
<div>
   <div class="px-2 pt-2">
      <p class="text-[30px] text-gray-600">TO DO LIST</p>
   </div>
   <div class="px-4">
      
      <ToDoListTable 
         :todos="todos" 
         :client="user.client"
      />
   </div>
</div>


</UserLayout>
   
</template>
<script setup>
import UserLayout from '../../Layouts/UserLayout.vue';
import ToDoListTable from '../../Components/ToDoListTable.vue';

const props = defineProps({
   user: Object,
   todos: Array

})

</script>