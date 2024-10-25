<template>
   <div>
      <canvas ref="chartCanvas"></canvas>
   </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
   months: {
      type: Array,
      required: true
   }, 
   totals: {
      type: Array,
      required: true
   }
});

const chartCanvas = ref(null);
let chartInstance = null;

// Create an array of all months
const allMonths = [
  'January', 'February', 'March', 'April',
  'May', 'June', 'July', 'August',
  'September', 'October', 'November', 'December'
];

// Function to get data for all months
const getAllMonthsData = () => {
  const data = new Array(12).fill(0);
  props.months.forEach((month, index) => {
    const monthIndex = allMonths.indexOf(month);
    if (monthIndex !== -1) {
      data[monthIndex] = Math.round(props.totals[index]); // Round to whole numbers
    }
  });
  return data;
};

onMounted(() => {
   if(chartCanvas.value) {
      chartInstance = new Chart(chartCanvas.value, {
         type: 'bar',
         data: {
            labels: allMonths,
            datasets: [
               {
                  label: 'Gym Visits',
                  data: getAllMonthsData(),
                  backgroundColor: '#4CAF50',
                  borderRadius: 2,
               },
            ],
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  position: 'top',
               },
               tooltip: {
                  callbacks: {
                     label: function(context) {
                        return `Visits: ${Math.round(context.raw)}`;
                     }
                  }
               }
            },
            scales: {
               y: {
                  beginAtZero: true,
                  ticks: {
                     stepSize: 1,
                     callback: function(value) {
                        return Math.round(value);
                     }
                  }
               }
            }
         }
      })
   }
});

onBeforeUnmount(() => {
   if(chartInstance) chartInstance.destroy();
});
</script>

<style scoped>
div {
  position: relative;
  height: 400px;
  width: 600px;
}
</style>