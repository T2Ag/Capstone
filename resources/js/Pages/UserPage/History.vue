<template>
<UserLayout>

   <div class="p-4">
      <div class="pb-8">
         <h1 class="text-3xl font-bold text-gray-800">
            Gym History
         </h1>
      </div>

      <div class="px-4">
         <div class="bg-white shadow-md rounded overflow-hidden p-3 ">
            <!-- Select Dropdown for choosing Logs or Transactions -->
            <div class="mb-2 flex justify-end">
               <label for="tableSelect" class="text-gray-700"></label>
               <select id="tableSelect" v-model="selectedTable" class="form-select w-[10rem]">
                  <option value="logs">Logs</option>
                  <option value="transactions">Transactions</option>
               </select>
            </div>

            <!-- Table for Logs -->
            <div v-if="selectedTable === 'logs'">
               <div class="bg-white shadow-md rounded overflow-hidden p-3">
               <table class="w-full text-left text-gray-500 bg-white">
                  <thead class="text-l text-700 uppercase bg-gray-100">
                     <tr class="text-center">
                     <th scope="col" class="lg:px-5 px-3 py-3">Log ID</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Payment Method</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Status</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Date</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Time</th>

                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="log in logs.data" :key="log.id" class="text-center">
                        <td>{{ log.id }}</td>
                        <td class="py-2 px-3">{{ log.payment_method ? log.payment_method.type: '' }}</td>
                        <td class="py-2 px-3">{{ log && log.transaction_id ? "Paid" : "Unpaid" }}</td>
                        <td>{{ formatDate(log.date) }}</td>
                        <td>{{ formatTime(log.date) }}</td>
                     </tr>
                  </tbody>
               </table>

               <Pagination class="flex mt-4 justify-end" :links="logs.links" />

               </div>
            </div>

            <!-- Table for Transactions -->
            <div v-if="selectedTable === 'transactions'">
               <div class="bg-white shadow-md rounded overflow-hidden p-3">
               <table class="w-full text-left text-gray-500 bg-white">
                  <thead class="text-l text-700 uppercase bg-gray-100">
                     <tr class="text-center">
                     <th scope="col" class="lg:px-5 px-3 py-3">Transaction ID</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Descrption</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Transaction Date</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Time</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Duration</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Total Amount</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="transaction in transactions.data" :key="transaction.id" class="text-center">
                        <td>{{ transaction.id }}</td>
                        <td class="py-2 px-3">{{ transaction.description }}</td>
                        <td>{{ formatDate(transaction.transaction_date) }}</td>
                        <td>{{ formatTime(transaction.transaction_date) }}</td>
                        <td>
                           <span v-if="transaction.start_date && transaction.end_date">
                              {{ formatDate(transaction.start_date) }} - {{ formatDate(transaction.end_date) }}
                           </span>
                           <span v-else>-</span>
                        </td>
                        <td>{{ transaction.total_amount }}</td>

                     </tr>
                  </tbody>
               </table>

               <Pagination class="flex mt-4 justify-end" :links="transactions.links" />

               </div>
            </div>
         </div>
      </div>
   </div>

   
</UserLayout>
</template>

<script setup>
import UserLayout from '../../Layouts/UserLayout.vue';
import Pagination from '../../Components/Pagination.vue';
import { ref } from 'vue';

const props = defineProps({
   user: Object,
   logs: (Array,Object),
   transactions: (Array,Object),
   success: String,
})

const selectedTable = ref('logs');

function formatDate(dateString) {
   const options = { year: 'numeric', month: 'numeric', day: 'numeric' };
   return new Date(dateString).toLocaleDateString(undefined, options);
}

function formatTime(dateString) {
   const options = { hour: 'numeric', minute: 'numeric', second: 'numeric' };
   return new Date(dateString).toLocaleTimeString(undefined, options);
}

</script>