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

      <div>
         <div class="font-bold">
            <p class="text-lg">DIRI IBUTANG MGA TABLE</p>
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

   </Layout>

</template>

<script setup>

import Layout from '@/Layouts/Layout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref,computed } from 'vue';

const props = defineProps({
   client: Object,
   payment_methods: Array
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
</script>