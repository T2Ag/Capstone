<template>

<Layout>
   <div class="px-2 py-2">
      <p class="text-[30px] text-gray-600">LOGS LIST</p>
   </div>

   <!-- Button para sa modal -->
   <div class="flex justify-end my-2">
      <button type="button" class="rounded text-white px-3 py-2 bg-red-700" data-bs-toggle="modal" data-bs-target="#createModal">
         Add Log
      </button>
   </div>

   <!-- Giimport gikan sa CraeteLogModal -->
   <CreateLogModal :clients="clients"/>

   <div class="mx-2">
      <div class="bg-white shadow-md rounded overflow-hidden p-3">
         <table class="w-full text-left text-gray-500 bg-white">
            <thead class="text-l text-700 uppercase bg-gray-100">
               <tr class="text-center">
                  <th scope="col" class="lg:px-5 px-3 py-3">CLIENT</th>
                  <th scope="col" class="lg:px-5 px-3 py-3">Date</th>
                  <th scope="col" class="lg:px-5 px-3 py-3">Time</th>
                  <th scope="col" class="lg:px-5 px-3 py-3">Actions</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="log in logs" :key="log.id" class="text-center ">
                  <td>{{ log.client.first_name }} {{ log.client.last_name }}</td>
                  <td>{{ formatDate(log.date) }}</td>
                  <td>{{ formatTime(log.date) }}</td>

                  <td>

                     <button class="text-red-600 mx-2" type="button" @click="openDeleteModal(log)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                           <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                        </svg>  
                     </button>
                     
                  </td>
               </tr>
            </tbody>
         </table>
      </div>

      <!-- Delete Log Modal -->
      <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="deleteModalTitle">Delete Log</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  Are you sure you want to delete Log # - {{deleteForm.id}} - {{deleteForm.first_name}} {{deleteForm.last_name}}?
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-danger" @click="deleteLog">Delete</button>
               </div>
            </div>
         </div>
      </div>

   </div>

</Layout>

</template>
 
<script setup>
import Layout from '@/Layouts/Layout.vue';
import CreateLogModal from '@/Components/LogModals/CreateLogModal.vue'
import { useForm } from '@inertiajs/vue3';


const props = defineProps({
   logs: Array,
   clients: Array
})

const deleteForm = useForm({
   id: null,
   client_id: '',
   first_name: '',
   last_name: '',

})

const openDeleteModal = (log) => {
   deleteForm.id = log.id;
   deleteForm.client_id = log.client_id;
   deleteForm.first_name = log.client.first_name;
   deleteForm.last_name = log.client.last_name;

}

function formatDate(dateString) {
   const options = { year: 'numeric', month: 'long', day: 'numeric' };
   return new Date(dateString).toLocaleDateString(undefined, options);
}

function formatTime(dateString) {
   const options = { hour: 'numeric', minute: 'numeric', second: 'numeric' };
   return new Date(dateString).toLocaleTimeString(undefined, options);
}

const deleteLog = () => {
   deleteForm.delete(route('logs.destroy', deleteForm.id), {
      onError: (errors) => {
         console.error(errors);
      },
      onSuccess: () => {
         const modalElement = document.querySelector('#deleteModal');
         if(modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
               modal.hide();
            }
         }
      }
   });
};
</script>