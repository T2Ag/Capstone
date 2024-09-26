<template>
   <Layout>
      <div class="logs-list">
         <ul>
            <li v-for="log in logs" :key="log.id" class="flex">
               <p class="m-2">{{ log.client.first_name }} {{ log.client.last_name }}</p> 
               <button type="button" class="rounded text-white px-3 py-2 bg-red-700" data-bs-toggle="modal" data-bs-target="#transactionModal" @click="openTransactionForm(log)">
                  Pay
               </button>
            </li>
         </ul>
      </div>
      
      <!-- Transaction Modal -->
      <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="transactionModalTitle">Transaction</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="resetTransactionForm"></button>
               </div>
               <div class="modal-body">

                  <p><strong>Client:</strong> {{ transactionForm.first_name }} {{ transactionForm.last_name }}</p>
                  <p><strong>Registration:</strong> {{ transactionForm.registration }}</p>
                  <p><strong>Payment Method:</strong> {{ transactionForm.payment_method }}</p>
                  <p v-if="transactionForm.startDate && transactionForm.endDate"><strong>Start Date:</strong> {{ transactionForm.startDate }}</p>
                  <p v-if="transactionForm.startDate && transactionForm.endDate"><strong>End Date:</strong> {{ transactionForm.endDate }}</p>
                  <p><strong>Total Amount:</strong> {{ transactionForm.totalAmount }}</p>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="resetTransactionForm">Close</button>
                  <button type="button" class="btn btn-danger" @click="submit">Pay</button>
               </div>
            </div>
         </div>
      </div>
   </Layout>
</template>

<script setup>
import Layout from '@/Layouts/Layout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  logs: Array,
});

const transactionForm = useForm({

   log_id: '',
   client_id: '',
   first_name: '',
   last_name: '',
   registration: '',
   payment_method: '',
   totalAmount: '',
   startDate: '',
   endDate: '',

})

const resetTransactionForm = () => {
  transactionForm.reset(); // Reset the form fields
};

const openTransactionForm = (log) => {
   transactionForm.log_id = log.id;
   transactionForm.client_id = log.client.id;
   transactionForm.first_name = log.client.first_name;
   transactionForm.last_name = log.client.last_name;

   const registrationType = log.client.registration ? log.client.registration.type : 'No Registration';
   const paymentMethodType = log.client.payment_method ? log.client.payment_method.type : 'No Payment Method';

   transactionForm.registration = registrationType;
   transactionForm.payment_method = paymentMethodType;

   if (paymentMethodType === 'walk-in') {
      if (registrationType === 'student') {
         transactionForm.totalAmount = 50.00;
      } else if (registrationType === 'regular') {
         transactionForm.totalAmount = 100.00;
      } else if (registrationType === 'senior citizen') {
         transactionForm.totalAmount = 75.00;
      }
   } else if (paymentMethodType === 'monthly') {
      const startDate = new Date();
      transactionForm.startDate = startDate.toISOString().split('T')[0];

      const endDate = new Date();
      endDate.setMonth(endDate.getMonth() + 1); 
      transactionForm.endDate = endDate.toISOString().split('T')[0];

      if (registrationType === 'student') {
         transactionForm.totalAmount = 500.00;
      } else if (registrationType === 'regular') {
         transactionForm.totalAmount = 600.00;
      } else if (registrationType === 'senior citizen') {
         transactionForm.totalAmount = 550.00;
      }
   }
   else {
      transactionForm.totalAmount = 0; // Default if no payment method is selected
   }
   
}

const submit = () => {
   transactionForm.post(route('transactions.create'), {
    onSuccess: () => {
      transactionForm.reset();
       const modalElement = document.querySelector('#transactionModal');
       if (modalElement) {
         const modal = bootstrap.Modal.getInstance(modalElement);
         if (modal) {
           modal.hide();
         }
       }
    },
    onError: (errors) => {
      errors.value = errors;
    },
  });
};

</script>
