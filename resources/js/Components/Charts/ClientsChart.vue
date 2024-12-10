<template>
   <div class="w-full">
       <canvas ref="chartCanvas"></canvas>
   </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';

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

// Function to create/update chart
const createChart = () => {
   if (chartCanvas.value) {
       const ctx = chartCanvas.value.getContext('2d');
       
       // Destroy existing chart if it exists
       if (chartInstance) {
           chartInstance.destroy();
       }

       chartInstance = new Chart(ctx, {
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
                   },
                   x: {
                       ticks: {
                           // Make x-axis labels responsive
                           maxRotation: 45,
                           minRotation: 45,
                           autoSkip: true,
                           autoSkipPadding: 10,
                           font: {
                               size: window.innerWidth < 768 ? 10 : 12
                           }
                       }
                   }
               }
           }
       });
   }
};

// Handle window resize
let resizeTimeout;
const handleResize = () => {
   clearTimeout(resizeTimeout);
   resizeTimeout = setTimeout(() => {
       createChart();
   }, 250);
};

onMounted(() => {
   createChart();
   window.addEventListener('resize', handleResize);
});

onBeforeUnmount(() => {
   if (chartInstance) chartInstance.destroy();
   window.removeEventListener('resize', handleResize);
   clearTimeout(resizeTimeout);
});

// Watch for changes in props
watch([() => props.months, () => props.totals], () => {
   createChart();
}, { deep: true });
</script>

<style scoped>
.w-full {
   position: relative;
   height: 400px;
   width: 100%;
}

/* Responsive height adjustments */
@media (max-width: 768px) {
   .w-full {
       height: 300px;
   }
}

@media (max-width: 480px) {
   .w-full {
       height: 250px;
   }
}
</style>