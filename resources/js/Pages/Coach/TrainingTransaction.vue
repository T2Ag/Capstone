<template>

   <Layout>
      <div class="p-3">
         <div class="flex justify-between mb-5">
            <div class="">
               <p class="text-3xl font-bold text-gray-900 ">Training Transaction List</p>
            </div>
            <div class="">
               <form @submit.prevent="filterTransactions" >
                  <InputField
                     type="search"
                     label=""
                     icon="search"
                     placeholder="Search..."
                     v-model="filterForm.search"
                  />
               </form>
            </div>
         </div>

         <div class="bg-white shadow-md rounded overflow-hidden p-3 m-2">

            <div class="flex justify-between align-middle pb-2">

               <div class="flex items-center justify-between p-4 rounded-lg shadow-sm">
                  <p class="text-xl font-semibold">Total Earnings:</p>
                  <p class="text-xl font-bold ml-5 bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-red-400">₱ {{ roundOff(totalEarnings) }}</p>
               </div>

               <div class="flex">
                  <div class="text-center pr-2">
                     <label for="date_filter" class="text-gray-500">Year</label>

                     <select v-model="filterForm.year_filter" @change="filterTransactions" class="form-select" name="year_filter">
                           <option value="all">All Years</option>
                           <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                     </select>
                  </div>
                  <div class="text-center pr-2">
                     <label for="month_filter" class="text-gray-500">Month</label>
                     <select v-model="filterForm.month_filter" @change="filterTransactions" class="form-select" name="month_filter">
                           <option value="all">All Months</option>
                           <option v-for="month in months" :key="month.value" :value="month.value">{{ month.label }}</option>
                     </select>
                  </div>               
               </div>
            </div>

            <div class="bg-white shadow-md rounded overflow-hidden p-3">
               <table class="w-full text-left text-gray-500 bg-white">
                  <thead class="text-l text-700 uppercase bg-gray-100">
                     <tr class="text-center lg:text-[15px] text-[7px]">

                        <th scope="col" class="lg:px-5 px-3 py-3">Transaction ID</th>
                        <th scope="col" class="lg:px-5 px-3 py-3">Client Name</th>
                        <th scope="col" class="lg:px-5 px-3 py-3">Training</th>
                        <th scope="col" class="lg:px-5 px-3 py-3">Coach Name</th>
                        <!-- <th scope="col" class="lg:px-5 px-3 py-3">Duration</th> -->
                        <th scope="col" class="lg:px-5 px-3 py-3">Total</th>
                        <th scope="col" class="lg:px-5 px-3 py-3">Actions</th>


                     </tr>
                  </thead>
                  <tbody>

                     <tr v-for="transaction in trainingTransactions.data" :key="transaction.id" class="text-center lg:text-[15px] text-[7px]">

                        <td class="py-3">{{ transaction.id }}</td>
                        
                        <td>
                           <span v-if="transaction.client">
                              {{ transaction.client.first_name }} 
                              {{ transaction.client.middle_initial || '' }} 
                              {{ transaction.client.last_name }}
                           </span>
                           <span v-else>
                              -
                           </span>
                        </td>
                        <td>{{ transaction.training?.name ?? '-' }}</td>
                        <td>
                           {{
                              transaction.training?.coach 
                                 ? transaction.training.coach.first_name + ' ' + 
                                 transaction.training.coach.middle_initial + ' ' + 
                                 transaction.training.coach.last_name 
                                 : '-'
                           }}
                        </td>
                        <!-- <td>{{ formatDate(transaction.start_date) }} - {{ formatDate(transaction.end_date) }}</td> -->
                        <td>₱ {{ transaction.total_amount }}</td>
                        <td>
                           <button class="text-red-600 mx-2" type="button" @click="openDeleteModal(transaction)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                 <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                              </svg>  
                           </button>    
                        </td>

                     </tr>

                  </tbody>
               </table>

               <Pagination class="flex mt-4 justify-end" :links="trainingTransactions.links" />

            </div>
         </div>   
      </div>
      

      
      <!-- Delete Log Modal -->
      <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="deleteModalTitle">Delete Training</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  Are you sure you want to delete Training Transaction # - {{deleteForm.id}}
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-danger" @click="deleteTraining">Delete</button>
               </div>
            </div>
         </div>
      </div>

   </Layout>

</template>



<script setup>

import Layout from '@/Layouts/Layout.vue';
import Pagination from '../../Components/Pagination.vue';
import InputField from '../../Components/InputField.vue';
import { useForm, router } from '@inertiajs/vue3';
import {ref, computed} from 'vue';

const props = defineProps({
   trainingTransactions: (Array,Object),
   year_filter: String,
   month_filter: String,
   search: String,
   totalEarnings: Number
})

function formatDate(dateString) {
   const options = { year: 'numeric', month: 'long', day: 'numeric' };
   return new Date(dateString).toLocaleDateString(undefined, options);
}
const formatTime = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true }); 
};

const deleteForm = useForm({
   id: '',
});

const openDeleteModal = (transaction) => {
   deleteForm.id = transaction.id
};

const deleteTraining = () => {
   deleteForm.delete(route('trainingTransactions.destroy', deleteForm.id), {
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

const roundOff = (value) => {
   return (Math.round(value * 100) / 100).toFixed(2);
};

const currentYear = new Date().getFullYear();
const years = computed(() => Array.from({ length: currentYear - 2020 + 1 }, (_, i) => 2020 + i));

const months = [
   { value: '01', label: 'January' },
   { value: '02', label: 'February' },
   { value: '03', label: 'March' },
   { value: '04', label: 'April' },
   { value: '05', label: 'May' },
   { value: '06', label: 'June' },
   { value: '07', label: 'July' },
   { value: '08', label: 'August' },
   { value: '09', label: 'September' },
   { value: '10', label: 'October' },
   { value: '11', label: 'November' },
   { value: '12', label: 'December' }
];

const filterForm = useForm({
   year_filter: props.year_filter || 'all',
   month_filter : props.month_filter  || 'all',
   search : props.search || ''
})

const filterTransactions = () => {
   router.get(route('trainingTransactions.index'), { 
      year_filter: filterForm.year_filter,
      month_filter: filterForm.month_filter,
      search: filterForm.search
   }, {
      preserveState: true,
      preserveScroll: true,
   });
};
</script>