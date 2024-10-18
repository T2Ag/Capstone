<template>
  <Layout>
    <div class="px-2 py-2">
        <p class="text-[30px] text-gray-600">PENDING PAYMENTS</p>
    </div>

    <div class="px-2 py-2">
      <div class="bg-white shadow-md rounded overflow-hidden p-3">
        <table class="w-full text-left text-gray-500 bg-white">
            <thead class="text-lg font-semibold uppercase bg-gray-100">
              <tr class="text-center">
                  <th scope="col" class="lg:px-5 px-3 py-3">Client Name</th>
                  <th scope="col" class="lg:px-5 px-3 py-3">Registration Type</th>
                  <th scope="col" class="lg:px-5 px-3 py-3">Payment Method</th>
                  <th scope="col" class="lg:px-5 px-3 py-3">Date</th>
                  <th scope="col" class="lg:px-5 px-3 py-3">Overdue</th>
                  <th scope="col" class="lg:px-5 px-3 py-3">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="log in allLogs" :key="log.id" class="text-center border-b hover:bg-gray-50">
                  <td class="lg:px-5 px-3 py-3">{{ log.client.first_name }} {{ log.client.last_name }}</td>
                  <td class="lg:px-5 px-3 py-3">{{ log.client.registration.type }}</td>
                  <td class="lg:px-5 px-3 py-3">{{ log.client.payment_method.type }}</td>
                  <td class="lg:px-5 px-3 py-3 ">
                      <div class="flex flex-col">
                        <div>{{ formatDate(log.date) }}</div>
                        <div>{{ formatTime(log.date) }}</div>
                      </div>
                    </td>
                  <td class="lg:px-5 px-3 py-3">{{ calculateOverdueDays(log.client.transactions, log.client.payment_method.type) }}</td>
                  <td class="lg:px-5 px-3 py-3">
                    <button type="button" class="rounded text-white px-3 py-2 bg-red-700" data-bs-toggle="modal" data-bs-target="#transactionModal" @click="resetTransactionForm; openTransactionForm(log) ">
                        Pay
                    </button>
                  </td>
              </tr>
            </tbody>
        </table>
      </div>
    </div>


    <!-- Transaction Modal -->
    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="transactionModalTitle">Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="clientName" class="form-label"><strong>Client:</strong></label>
                    <div class="flex">
                      <p class="pr-1"> {{ transactionForm.first_name }} </p>
                      <p> {{ transactionForm.last_name }} </p>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="registrationType" class="form-label"><strong>Registration:</strong></label>
                    <p> {{ transactionForm.registration }} </p>
                </div>
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label"><strong>Payment Method:</strong></label>
                    <p> {{ transactionForm.payment_method }} </p>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
                <button type="button" class="btn btn-danger" @click="submit">Pay</button>
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
 </Layout>
</template>

<script setup>
import Layout from '@/Layouts/Layout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref,computed } from 'vue';

// Define props for the grouped logs
const props = defineProps({
 logs: Array
});

const errors = ref({});
  const header = ref('');
  const alertMessage = ref('');

// Initialize the form for transaction details
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
});

// Computed properties for log grouping
const groupedLogs = computed(() => {
  const monthlyLogs = props.logs.filter(log => log.client.payment_method.type === 'monthly');
  const groupedByClient = {};
  
  monthlyLogs.forEach(log => {
    if (!groupedByClient[log.client.id]) {
      groupedByClient[log.client.id] = log;
    }
  });
  
  return Object.values(groupedByClient);
});

const nonMonthlyLogs = computed(() => {
  return props.logs.filter(log => log.client.payment_method.type !== 'monthly');
});

const allLogs = computed(() => {
  return [...groupedLogs.value, ...nonMonthlyLogs.value];
});

// Function to calculate overdue days
const calculateOverdueDays = (transactions, payment_method) => {

  if (!transactions || transactions.length === 0 || payment_method !== 'monthly') {
    return '-'; // No transactions or not monthly, so display "-"
  }
  
  // Sort transactions by end_date in descending order
  const sortedTransactions = [...transactions].sort((a, b) =>
    new Date(b.end_date) - new Date(a.end_date)
  );

  const lastTransaction = sortedTransactions[0];
  const lastEndDate = new Date(lastTransaction.end_date);
  const today = new Date();

  // If the last end date is in the future, not overdue
  if (lastEndDate > today) {
    return 0;
  }

  // Calculate the difference in days
  const diffTime = Math.abs(today - lastEndDate);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  
  const formattedEndDate = lastEndDate.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  // Return the result with the number of days and the formatted end date
  return diffDays === 1
    ? `1 day since ${formattedEndDate}`
    : `${diffDays} days since ${formattedEndDate}`;
};

// Reset the transaction form
const resetTransactionForm = () => {
 transactionForm.reset(); // Reset the form fields
};

// Populate the transaction form based on the selected log
const openTransactionForm = (log) => {
  transactionForm.log_id = log.id;
  transactionForm.client_id = log.client.id;
  transactionForm.first_name = log.client.first_name;
  transactionForm.last_name = log.client.last_name;

  const registrationType = log.client.registration?.type || 'No Registration';
  const paymentMethodType = log.client.payment_method?.type || 'No Payment Method';

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
      const startDate = new Date(log.date);
      transactionForm.startDate = startDate.toISOString().split('T')[0];

      const endDate = new Date(startDate);
      endDate.setMonth(endDate.getMonth() + 1);
      transactionForm.endDate = endDate.toISOString().split('T')[0];
    }
  }
};

// Submit the transaction form and handle modal close on success
const submit = () => {
 transactionForm.post(route('transactions.create'), {
   onSuccess: () => {
     
     const modalElement = document.querySelector('#transactionModal');
     if (modalElement) {
       const modal = bootstrap.Modal.getInstance(modalElement);
       if (modal) {
         modal.hide();
       }
     }

     header.value = 'Payment Successful, Please Proceed';
     alertMessage.value = `Payment for ${transactionForm.first_name} ${transactionForm.last_name}. Payment confirmed.`;

     showAlertModal();
     transactionForm.reset();
   },
   onError: (errors) => {
     errors.value = errors;
   },
 });
};

function formatDate(dateString) {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

function formatTime(dateString) {
  const options = { hour: 'numeric', minute: 'numeric', second: 'numeric' };
  return new Date(dateString).toLocaleTimeString(undefined, options);
}

const showAlertModal = () => {
  const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
  alertModal.show();

  setTimeout(() => {
    alertModal.hide();
  }, 4000);
};
</script>
