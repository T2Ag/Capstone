<template>

   <Layout>
      <div class="flex justify-between">
         <div class="px-2 py-2">
            <p class="text-[30px] text-gray-600">TRANSACTION LIST</p>
         </div>
         <div class="px-2 py-2">
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

         <!-- Filter by Date -->
         <div class="flex justify-end pb-2">

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

            <div class="text-center pr-2">
               <label for="registration_type" class="text-gray-500">Registration Type</label>
               <select v-model="filterForm.registration_type" @change="filterTransactions" class="form-select" name="registration_type">
                     <option value="all">All Types</option>
                     <option v-for="registration in registrations" :key="registration.id" :value="registration.type">
                           {{ registration.type }}
                     </option>
               </select>
            </div>

            <div class="text-center pr-2">
               <label for="payment_method" class="text-gray-500">Payment Type</label>
               <select v-model="filterForm.payment_method" @change="filterTransactions" class="form-select" name="payment_method">
                     <option value="all">All Types</option>
                     <option v-for="paymentMethod in paymentMethods" :key="paymentMethod.id" :value="paymentMethod.type">
                           {{ paymentMethod.type }}
                     </option>
               </select>
            </div>

         </div>

         <div class="bg-white shadow-md rounded overflow-hidden p-3">
            <table class="w-full text-left text-gray-500 bg-white">
               <thead class="text-l text-700 uppercase bg-gray-100">
                  <tr class="text-center lg:text-[15px] text-[7px]">
                     <th scope="col" class="lg:px-5 px-3 py-3">Transaction ID</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Client Name</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Payment Type</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Duration</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Transaction Date</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Total</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="transaction in transactions.data" :key="transaction.id" class="text-center lg:text-[15px] text-[7px]">
                     <td>{{ transaction.id }}</td>
                     <td>{{ transaction.client.first_name }} {{ transaction.client.last_name }}</td>
                     <!-- Conditional logic to display "Monthly" or "Walk-in" -->
                     <td>
                        <span v-if="transaction.start_date && transaction.end_date">Monthly</span>
                        <span v-else>Walk-in</span>
                     </td>
                     <td>
                        <span v-if="transaction.start_date && transaction.end_date">{{ formatDate(transaction.start_date) }} - {{ formatDate(transaction.end_date) }}</span>
                        <span v-else>-</span>
                     </td>
                     <td>{{ formatDate(transaction.transaction_date) }} {{ formatTime(transaction.transaction_date) }}</td>
                     <td>{{ transaction.total_amount }}</td>
                  </tr>
               </tbody>
            </table>

            <Pagination class="flex mt-4 justify-end" :links="transactions.links" />

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
   transactions: (Array, Object),
   year_filter: String,
   month_filter: String,
   registrations: Array,
   paymentMethods: Array,
   search: String
})

function formatDate(dateString) {
   const options = { year: 'numeric', month: 'long', day: 'numeric' };
   return new Date(dateString).toLocaleDateString(undefined, options);
}
const formatTime = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true }); 
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
   registration_type : props.registration_type || 'all',
   payment_method : props.payment_method || 'all',
   search : props.search || ''
   // member_filter : props.member_filter || false,
   // date_filter : props.date_filter || ''
})

const filterTransactions = () => {
   router.get(route('transactions.index'), { 
      year_filter: filterForm.year_filter,
      month_filter: filterForm.month_filter,
      registration_type: filterForm.registration_type,
      payment_method: filterForm.payment_method,
      search: filterForm.search
      // member_filter : filterForm.member_filter,
      // date_filter: filterForm.date_filter
   }, {
      preserveState: true,
      preserveScroll: true,
   });
};

</script>