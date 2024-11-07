<template>
   
   <Layout>

      <div>
         <div class="flex justify-between align-middle items-center">
            <div class="text-[30px] font-semibold">
               {{ client.first_name }} {{ client.middle_initial }}. {{ client.last_name }}
            </div>
            <div>
               
               <button
                  type="button" 
                  class="rounded text-white px-3 py-2 bg-blue-500" 
                  data-bs-toggle="modal" 
                  data-bs-target="#transactionModal" 
                  @click="openTransactionForm(client)">
                     {{ client.payment_method.type === 'walk-in' ? 'Pay Session' : 'Renew Monthly' }}
               </button>

            </div>
         </div>

         <div class="flex justify-between">
            <div>
               Payment type: <span class="font-semibold ">{{ client.payment_method.type }}</span>
            </div>
            <button
                  type="button" 
                  class="rounded text-white text-sm px-2 py-1" 
                   :class="client.payment_method.type === 'walk-in' ? 'bg-green-500' : 'bg-gray-500'"
                  data-bs-toggle="modal" 
                  data-bs-target="#updatePaymentModal" 
                  @click="openUpdatePaymentForm(client)">
                     {{ client.payment_method.type === 'walk-in' ? 'Upgrade to Monthly' : 'Go back to Session' }}
            </button>

         </div>
         
         <div>
            {{ client.registration.type }}
         </div>
         
      </div>

      <div class="bg-white shadow-md rounded overflow-hidden p-3 m-2">
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
                  <th scope="col" class="lg:px-5 px-3 py-3">Date</th>
                  <th scope="col" class="lg:px-5 px-3 py-3">Time</th>
                  <th scope="col" class="lg:px-5 px-3 py-3">...</th>
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="log in logs.data" :key="log.id" class="text-center">
                     <td>{{ log.id }}</td>
                     <td>{{ formatDate(log.date) }}</td>
                     <td>{{ formatTime(log.date) }}</td>
                     <td>
                        <button class="text-red-600 mx-2" type="button" @click="openLogDeleteModal(log)" data-bs-toggle="modal" data-bs-target="#deleteModal">
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
                     <td>{{ formatDate(transaction.transaction_date) }}</td>
                     <td>{{ formatTime(transaction.transaction_date) }}</td>
                     <td>
                        <span v-if='client.payment_method.type === "walk-in"'>-</span>
                        <span v-else>{{ formatDate(transaction.start_date) }} - {{ formatDate(transaction.end_date) }}</span>
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
                  <div v-if="transactionForm.startDate && transactionForm.endDate" class="mb-3">
                     <label for="startDate" class="form-label"><strong>Start Date:</strong></label>
                     <input type="date" id="startDate" class="form-control" v-model="transactionForm.startDate">
                  </div>
                  <div v-if="transactionForm.startDate && transactionForm.endDate" class="mb-3">
                     <label for="endDate" class="form-label"><strong>End Date:</strong></label>
                     <input type="date" id="endDate" class="form-control" v-model="transactionForm.endDate">
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
      <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="deleteModalTitle">Delete Log</h5>
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
   </Layout>

</template>

<script setup>
import Pagination from '../../Components/Pagination.vue';
import Layout from '@/Layouts/Layout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref,computed } from 'vue';

const props = defineProps({
   client: Object,
   payment_methods: Array,
   createdLogTransaction: Object,
   logs: (Array, Object),
   transactions: (Array, Object)
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
const openTransactionForm = (client) => {
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
      'regular': 100.00,
      'senior citizen': 75.00
    },
    'monthly': {
      'student': 500.00,
      'regular': 600.00,
      'senior citizen': 550.00
    }
  };

  if (paymentMethodType === 'walk-in' || paymentMethodType === 'monthly') {
    transactionForm.totalAmount = prices[paymentMethodType][registrationType] || 0;

    if (paymentMethodType === 'monthly') {
      const startDate = new Date();
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

function formatDate(dateString) {
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

</script>