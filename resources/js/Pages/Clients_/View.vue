<template>
   
   <Layout>
      <div class="m-4">
         <Link :href="route('clients')" class="inline-flex items-center hover:text-gray-700">
            <i class="bi bi-arrow-left text-lg mr-2"></i>
         </Link>

         <div class="flex flex-col m-3">

            <div 
               v-if="qrCode" 
               v-html="qrCode" 
               class="border flex justify-center items-center" 
               style="width: 200px; height: 200px;"
            ></div>

            <!-- No QR Code Fallback -->
            <div 
               v-else 
               class="border text-gray-500 text-center flex justify-center items-center" 
               style="width: 200px; height: 200px;"
            >
               No QR Code Available
            </div>

            <!-- QR Code Label -->
            <div class="mt-1 text-center w-[200px]">
               <p>Personal QR Code</p>
            </div>

         </div>



         <div class="">

            <div class="flex justify-between align-middle items-center pb-4">
               
               <div class="flex">
                  <div class="text-[30px] font-semibold mr-3">
                     {{ client.first_name }} {{ client.middle_initial }}. {{ client.last_name }}
                  </div>
                  <div class="my-auto align-middle mx-3 text-[20px]"
                     :class="{
                        'text-green-600 font-semibold': client.isMonthlyActive,
                        'text-red-600 font-semibold': !client.isMonthlyActive,
                        'hidden': client.payment_method.type === 'walk-in'
                     }"
                  >
                     ({{ client.isMonthlyActive ? 'Active' : 'Expired' }})
                  </div>
                  <div class="flex my-auto align-middle">
                     <p class="text-[20px]">Expire on: 
                        <span class="font-bold">
                           {{ latestMonthlyTransaction && latestMonthlyTransaction.end_date ? formatWordMonthDate(latestMonthlyTransaction.end_date) : '-' }}
                        </span>
                     </p>
                  </div>
               </div>

                  <div>
                     <button
                        type="button" 
                        class="rounded text-white text-xl px-4 py-3" 
                        :class="{
                           'bg-blue-500': !client.isMonthlyActive,
                           'bg-gray-400 cursor-not-allowed': client.isMonthlyActive
                        }"
                        :disabled="client.isMonthlyActive"
                        data-bs-toggle="modal" 
                        data-bs-target="#transactionModal" 
                        @click="openTransactionForm(client)">
                           {{ client.payment_method.type === 'walk-in' ? 'Pay Session' : 'Renew Monthly' }}
                     </button>  
                  </div>
            </div>

            <div class="flex justify-between py-2">
               <div class="text-xl flex">
                  <div class="mr-2">
                     Payment type:    
                  </div>
                  <div class="font-bold">
                     {{ client.payment_method.type }}</div>
               </div>
               <div>
                  <button
                     type="button" 
                     class="rounded text-white text-lg px-3 py-2" 
                     :class="{
                           'bg-green-500': client.payment_method.type === 'walk-in',
                           'bg-gray-500': client.payment_method.type !== 'walk-in'  && !client.isMonthlyActive,
                           'bg-gray-400 cursor-not-allowed': client.isMonthlyActive
                     }"
                     :disabled="client.isMonthlyActive"
                     data-bs-toggle="modal" 
                     data-bs-target="#updatePaymentModal" 
                     @click="openUpdatePaymentForm(client)">
                           {{ client.payment_method.type === 'walk-in' ? 'Upgrade to Monthly' : 'Go back to Session' }}
                  </button>
                 
               </div>
              

            </div>
            
            <div class="flex py-2 mb-3 text-xl">
               <div class="mr-2">
                  Registration:
               </div>
               <div class="font-bold">
                  {{ client.registration.type }}
               </div>
            </div>

            <div class="flex justify-between py-2 mb-3 text-xl">
               <div class="flex my-auto align-middle">
                  <div class="mr-2">
                     Membership status:
                  </div>
                  <div class="font-bold">
                     {{ isMember(client) }}
                  </div>                  
               </div>

               <button
                  v-if="!client.user"
                  type="button"
                  class="rounded text-white text-lg px-3 py-2 bg-green-500"
                  data-bs-toggle="modal"
                  data-bs-target="#updateMembershipModal"
               >
                  Grant Membership
               </button>

               <button
                  v-else
                  type="button"
                  class="rounded text-white text-lg px-3 py-2 bg-red-700"
                  data-bs-toggle="modal"
                  data-bs-target="#revokeMembership"
               >
                  Revoke Membership
               </button>

               <MembershipModal :client="client" :users="users" />
            </div>

            <div class="flex justify-between">
               <div class="text-xl mr-3 mb-4">
                  Username: <span class="font-bold">{{ client.user ? client.user.username : '-' }}</span>
               </div>

               <div v-if="client.user_id">
                  <Link
                     :href="route('users.view', { user: client.user_id })"
                     class="inline-flex align-middle text-sm items-center px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition"
                  >
                     <i class="bi bi-pencil mr-2"></i>
                     Edit Client User Account
                  </Link>
               </div>
            </div>

         </div>

         <div v-if="success" class="px-2 py-1 my-3 bg-green-200 rounded border-1 border-green-500 ">
            <SuccessMessages :success="success" class="p-3"/>
         </div>   

         <div class="mb-3">
            <ToDoListTable 
               :todos="todos" 
               :client="client"
            />
         </div>


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
                     <th scope="col" class="lg:px-5 px-3 py-3">...</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="log in logs.data" :key="log.id" class="text-center">
                        <td>{{ log.id }}</td>
                        <td class="py-2 px-3">{{ log.payment_method ? log.payment_method.type: '' }}</td>
                        <td class="py-2 px-3">{{ log && log.transaction_id ? "Paid" : "Unpaid" }}</td>
                        <td>{{ formatDate(log.date) }}</td>
                        <td>{{ formatTime(log.date) }}</td>
                        <td>
                           <button class="text-red-600 mx-2" type="button" @click="openLogDeleteModal(log)" data-bs-toggle="modal" data-bs-target="#deleteLogModal">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                              <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                              </svg>
                           </button>
                        </td>
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
                     <th scope="col" class="lg:px-5 px-3 py-3">...</th>
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
                        <td>
                           <button class="text-red-600 mx-2" type="button" @click="openTransactionDeleteModal(transaction)" data-bs-toggle="modal" data-bs-target="#deleteTransactionModal">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                              <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                              </svg>
                           </button>
                        </td>
                     </tr>
                  </tbody>
               </table>

               <Pagination class="flex mt-4 justify-end" :links="transactions.links" />

               </div>
            </div>

            
         </div>

         <!-- Transaction Modal -->
         <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="transactionModalTitle">Transaction</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <div class="mb-3">
                        <label for="clientName" class="form-label"><strong>Client:</strong></label>
                        <div class="flex">
                           <p class="pr-1">{{ client.first_name }}</p>
                           <p class="pr-1">{{ client.middle_initial }}</p>
                           <p>{{ client.last_name }}</p>
                        </div>
                     </div>
                     <div class="mb-3">
                        <label for="registrationType" class="form-label"><strong>Registration:</strong></label>
                        <p>{{ client.registration.type }}</p>
                     </div>
                     <div class="mb-3">
                        <label for="paymentMethod" class="form-label"><strong>Payment Method:</strong></label>
                        <p>{{ client.payment_method.type }}</p>
                     </div>
                     <div v-if="client.payment_method.type ==='monthly'" class="mb-3">
                        <label for="startDate" class="form-label"><strong>Start Date:</strong></label>
                        <input type="date" id="startDate" class="form-control" v-model="transactionForm.startDate">
                     </div>

                     <div v-if="client.payment_method.type ==='monthly'" class="mb-3">
                        <label for="endDate" class="form-label"><strong>End Date:</strong></label>
                        <input type="date" id="endDate" class="form-control" v-model="transactionForm.endDate">

                        <span class="text-red-500">{{ transactionForm.errors.startDate }}</span>
                        <span class="text-red-500">{{ transactionForm.errors.endDate }}</span>
                     </div>

                     <div class="mb-3">
                        <label for="totalAmount" class="form-label"><strong>Total Amount:</strong></label>
                        <input type="number" id="totalAmount" class="form-control" v-model="transactionForm.totalAmount" placeholder="Total Amount">
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-danger" @click="submit">Pay</button>
                  </div>
               </div>
            </div>
         </div>

         <!-- UpdatePayment Modal -->
         <div class="modal fade" id="updatePaymentModal" tabindex="-1" aria-labelledby="updatePaymentModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="updatePaymentModalTitle">Update Payment</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <div v-if="client.payment_method.type === 'walk-in'">
                        <p> Would you like to upgrade to monthly payment?</p>
                     </div>
                     <div v-else>
                        <p>Would you like to switch back to session-based payment?</p>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-success" @click="update">Update</button>
                  </div>
               </div>
            </div>
         </div>

         <!-- Alert Modal -->
         <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-light shadow-lg rounded-lg">
               <div class="modal-body text-center py-5">
               <div class="mb-4">
                  {{ header }}
               </div>
               <div class="bg-white p-4 rounded-lg shadow-md">
                  <p>
                     {{ alertMessage }}
                  </p>
               </div>
               </div>
            </div>
         </div>
         </div>

         <!-- Delete Log Modal -->
         <div class="modal fade" id="deleteLogModal" tabindex="-1" aria-labelledby="deleteLogModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="deleteLogModalTitle">Delete Log</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     Are you sure you want to delete Log # - {{deleteLogForm.id}} - {{deleteLogForm.first_name}} {{deleteLogForm.last_name}}?
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-danger" @click="deleteLog">Delete</button>
                  </div>
               </div>
            </div>
         </div>

         <!-- Delete Transaction Modal -->
         <div class="modal fade" id="deleteTransactionModal" tabindex="-1" aria-labelledby="deleteTransactionModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="deleteTransactionModalTitle">Delete Transaction</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     Are you sure you want to delete Transaction # - {{deleteTransactionForm.id}} - {{deleteTransactionForm.first_name}} {{deleteTransactionForm.last_name}}?
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-danger" @click="deleteTransaction">Delete</button>
                  </div>
               </div>
            </div>
         </div>         
      </div>

      <!-- Revoke Membership Modal -->
      <div class="modal fade" id="revokeMembership" tabindex="-1" aria-labelledby="revokeMembershipTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="revokeMembershipTitle">Revoke Membership</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <div >
                     <p> Would you like to revoke the membership of {{ client.first_name }} {{ client ? client.middle_initial: '' }}. {{ client.last_name }}</p>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-danger" @click="revoke">Revoke</button>
               </div>
            </div>
         </div>
      </div>
      
   </Layout>

</template>

<script setup>
import Pagination from '../../Components/Pagination.vue';
import ToDoListTable from '../../Components/ToDoListTable.vue';
import MembershipModal from '../../Components/UserModals/MembershipModal.vue';
import SuccessMessages from '../../Components/SuccessMessages.vue';
import Layout from '@/Layouts/Layout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref,computed } from 'vue';

const props = defineProps({
   client: Object,
   users: Array,
   payment_methods: Array,
   createdLogTransaction: Object,
   logs: (Array, Object),
   transactions: (Array, Object),
   todos: Array,
   latestMonthlyTransaction: Object,
   firstUnpaidMonthlyLog: Object,
   success: String,
   qrCode: String 
 });

const transactionForm = useForm({
   client_id: '',
   first_name: '',
   last_name: '',
   registration: '',
   payment_method: '',
   totalAmount: '',
   startDate: '',
   endDate: '',
});

const selectedTable = ref('logs');
const header = ref('');
const alertMessage = ref('');

// Populate the transaction form based on the selected log
const openTransactionForm = (client, firstUnpaidMonthlyLog) => {
  transactionForm.client_id = client.id;
  transactionForm.first_name = client.first_name;
  transactionForm.last_name = client.last_name;

  const registrationType = client.registration?.type || 'No Registration';
  const paymentMethodType = client.payment_method?.type || 'No Payment Method';

  transactionForm.registration = registrationType;
  transactionForm.payment_method = paymentMethodType;

  // Reset dates and amount
  transactionForm.startDate = '';
  transactionForm.endDate = '';
  transactionForm.totalAmount = 0;

  const prices = {
    'walk-in': {
      'student': 50.00,
      'regular': 60.00,
      'senior citizen': 50.00
    },
    'monthly': {
      'student': 500.00,
      'regular': 600.00,
      'senior citizen': 500.00
    }
  };

  if (paymentMethodType === 'walk-in' || paymentMethodType === 'monthly') {
   
      let basePrice = prices[paymentMethodType][registrationType] || 0;

      // Adjust prices based on payment method
      if (paymentMethodType === 'walk-in' && !client.user_id) {
      basePrice += 10.00;
      } else if (paymentMethodType === 'monthly' && !client.user_id) {
      basePrice += 100.00;
      }

      transactionForm.totalAmount = basePrice;

    if (paymentMethodType === 'monthly') {

      const startDate = firstUnpaidMonthlyLog ? new Date(firstUnpaidMonthlyLog.date) : new Date();

      console.log(startDate)

      transactionForm.startDate = startDate.toISOString().split('T')[0];

      const endDate = new Date(startDate);
      endDate.setMonth(endDate.getMonth() + 1);
      transactionForm.endDate = endDate.toISOString().split('T')[0];
    }
  }
};

// Submit the transaction form and handle modal close on success
const submit = () => {
 transactionForm.post(route('transactions.createWithLog'), {
   onSuccess: () => {
     transactionForm.reset();
     const modalElement = document.querySelector('#transactionModal');
     if (modalElement) {
       const modal = bootstrap.Modal.getInstance(modalElement);
       if (modal) {
         modal.hide();
       }
     }

      // Set header and alert message based on response

      header.value = 'Payment Successful, Please Proceed';
      alertMessage.value = `Log and Transaction created for ${props.client.first_name} ${props.client.last_name}. Payment confirmed.`;
      
      showAlertModal();

   },
   onError: (errors) => {
     errors.value = errors;
   },
 });
};

const updatePaymentForm = useForm({

});

const openUpdatePaymentForm = (client) => {

}

const update = () => {
   updatePaymentForm.post(route('clients.updatePayment', props.client.id), {
     preserveState: true,
     preserveScroll: true,
     onSuccess: () => {
       // Close the modal
       const modalElement = document.querySelector('#updatePaymentModal');
       if (modalElement) {
         const modal = bootstrap.Modal.getInstance(modalElement);
         if (modal) {
           modal.hide();
         }
       }
       
       // Optionally, you can show a success message here
       // For example, if you're using a toast notification library:
       // toast.success('Payment method updated successfully');

     },
     onError: (errors) => {
       console.error(errors);

      },
   });
}

const showAlertModal = () => {
   const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
   alertModal.show();

   setTimeout(() => {
      alertModal.hide();
   }, 4000);
};

function isMember(client) {
   return client.user_id ? 'Member' : 'Non-member';
}

function formatDate(dateString) {
   const options = { year: 'numeric', month: 'numeric', day: 'numeric' };
   return new Date(dateString).toLocaleDateString(undefined, options);
}

function formatWordMonthDate(dateString) {
   const options = { year: 'numeric', month: 'long', day: 'numeric' };
   return new Date(dateString).toLocaleDateString(undefined, options);
}

function formatTime(dateString) {
   const options = { hour: 'numeric', minute: 'numeric', second: 'numeric' };
   return new Date(dateString).toLocaleTimeString(undefined, options);
}

const deleteLogForm = useForm({
   id: null,
   client_id: '',
   first_name: '',
   last_name: '',
});

const openLogDeleteModal = (log) => {
   deleteLogForm.id = log.id;
   deleteLogForm.client_id = log.client_id;
   deleteLogForm.first_name = log.client.first_name;
   deleteLogForm.last_name = log.client.last_name;
};

const deleteLog = () => {
   deleteLogForm.delete(route('clientLogs.destroy', deleteLogForm.id), {
      onError: (errors) => {
         console.error(errors);
      },
      onSuccess: () => {
         const modalElement = document.querySelector('#deleteLogModal');
         if(modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
               modal.hide();
            }
         }
      }
   });
};

const deleteTransactionForm = useForm({
   id: null,
   client_id: '',
   first_name: '',
   last_name: '',
});

const openTransactionDeleteModal = (transaction) => {
   deleteTransactionForm.id = transaction.id;
   deleteTransactionForm.client_id = transaction.client_id;
   deleteTransactionForm.first_name = transaction.client.first_name;
   deleteTransactionForm.last_name = transaction.client.last_name;
}

const deleteTransaction = () => {
   deleteTransactionForm.delete(route('clientTransactions.destroy', deleteTransactionForm.id), {
      onError: (errors) => {
         console.error(errors);
      },
      onSuccess: () => {
         const modalElement = document.querySelector('#deleteTransactionModal');
         if(modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
               modal.hide();
            }
         }
      }
   });
};

const revoke = () => {
   deleteLogForm.put(route('clients.revoke', props.client.id), {
      onError: (errors) => {
         console.error(errors);
      },
      onSuccess: () => {
         const modalElement = document.querySelector('#revokeMembership');
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