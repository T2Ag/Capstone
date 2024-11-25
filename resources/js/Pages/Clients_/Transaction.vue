<template>
   <Layout>
      <div class="p-3">
         <h1 class="text-3xl mb-5 font-bold text-gray-900 tracking-tight hover:text-gray-900 transition-colors">
            Transaction List
         </h1>

         <div class="bg-white shadow-md rounded overflow-hidden p-3 m-2">
            
            <div class="flex justify-between">
               <!-- Total Earnings -->
               <div class="flex mb-4">
                  <div class="bg-gradient-to-r from-gray-100 to-gray-200 p-3 rounded-lg shadow-md">
                     <span class="text-gray-600 font-semibold mr-2">Total Earnings:</span>
                     <span class="text-xl font-bold text-red-600">₱ {{ totalEarnings }}</span>
                  </div>
               </div>

               <!-- Filter Section -->
               <div class="flex justify-end pb-4 space-x-4">
                  <div class="relative">
                     <label class="block text-xs text-gray-600 mb-1">Year</label>
                     <div class="relative">
                        <select 
                           v-model="filterForm.year_filter" 
                           @change="filterTransactions" 
                           class="appearance-none w-full bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm text-gray-700"
                        >
                           <option value="all">All Years</option>
                           <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                           <i class="bi bi-chevron-down"></i>
                        </div>
                     </div>
                  </div>

                  <div class="relative">
                     <label class="block text-xs text-gray-600 mb-1">Month</label>
                     <div class="relative">
                        <select 
                           v-model="filterForm.month_filter" 
                           @change="filterTransactions" 
                           class="appearance-none w-full bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm text-gray-700"
                        >
                           <option value="all">All Months</option>
                           <option v-for="month in months" :key="month.value" :value="month.value">{{ month.label }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                           <i class="bi bi-chevron-down"></i>
                        </div>
                     </div>
                  </div>

                  <div class="relative">
                     <label class="block text-xs text-gray-600 mb-1">Registration Type</label>
                     <div class="relative">
                        <select 
                           v-model="filterForm.registration_type" 
                           @change="filterTransactions" 
                           class="appearance-none w-full bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm text-gray-700"
                        >
                           <option value="all">All Types</option>
                           <option v-for="registration in registrations" :key="registration.id" :value="registration.type">
                              {{ registration.type }}
                           </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                           <i class="bi bi-chevron-down"></i>
                        </div>
                     </div>
                  </div>

                  <div class="relative">
                     <label class="block text-xs text-gray-600 mb-1">Payment Type</label>
                     <div class="relative">
                        <select 
                           v-model="filterForm.payment_method" 
                           @change="filterTransactions" 
                           class="appearance-none w-full bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm text-gray-700"
                        >
                           <option value="all">All Types</option>
                           <option v-for="paymentMethod in paymentMethods" :key="paymentMethod.id" :value="paymentMethod.type">
                              {{ paymentMethod.type }}
                           </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                           <i class="bi bi-chevron-down"></i>
                        </div>
                     </div>
                  </div>
               </div>   
            </div>
            

            <!-- Transactions Table -->
            <table class="w-full text-left text-gray-600">
               <thead class="text-sm font-semibold uppercase bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700">
                  <tr class="text-center">
                     <th scope="col" class="lg:px-5 px-3 py-3">Transaction ID</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Client Name</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Payment Type</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Duration</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Transaction Date</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Total</th>
                  </tr>
               </thead>
               <tbody>
                  <tr 
                     v-for="transaction in transactions.data" 
                     :key="transaction.id" 
                     class="text-center bg-white hover:bg-gray-50 transition-colors duration-200"
                  >
                     <td class="py-2 px-3">{{ transaction.id }}</td>
                     <td class="py-2 px-3">{{ transaction.client.first_name }} {{ transaction.client.last_name }}</td>
                     <td class="py-2 px-3">
                        <span v-if="transaction.start_date && transaction.end_date">Monthly</span>
                        <span v-else>Walk-in</span>
                     </td>
                     <td class="py-2 px-3">
                        <span v-if="transaction.start_date && transaction.end_date">
                           {{ formatDate(transaction.start_date) }} - {{ formatDate(transaction.end_date) }}
                        </span>
                        <span v-else>-</span>
                     </td>
                     <td class="py-2 px-3">
                        {{ formatDate(transaction.transaction_date) }} {{ formatTime(transaction.transaction_date) }}
                     </td>
                     <td class="py-2 px-3">₱ {{ transaction.total_amount }}</td>
                  </tr>
               </tbody>
            </table>

            <!-- Pagination -->
            <div class="flex mt-4 justify-end">
               <Pagination class="flex space-x-2" :links="transactions.links" />
            </div>
         </div>
      </div>
   </Layout>
</template>

<script setup>
import Layout from '@/Layouts/Layout.vue';
import Pagination from '../../Components/Pagination.vue';
import { useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
   transactions: (Array, Object),
   year_filter: String,
   month_filter: String,
   registrations: Array,
   paymentMethods: Array,
   search: String,
   totalEarnings: Number
});

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
   month_filter: props.month_filter || 'all',
   registration_type: props.registration_type || 'all',
   payment_method: props.payment_method || 'all',
   search: props.search || ''
});

const filterTransactions = () => {
   router.get(route('transactions.index'), { 
      year_filter: filterForm.year_filter,
      month_filter: filterForm.month_filter,
      registration_type: filterForm.registration_type,
      payment_method: filterForm.payment_method,
      search: filterForm.search
   }, {
      preserveState: true,
      preserveScroll: true,
   });
};
</script>