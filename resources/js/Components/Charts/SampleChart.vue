<script setup>
import { ref, onMounted, computed } from 'vue';
import ApexCharts from 'apexcharts';

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

// Extract months and totals from props
const monthsData = computed(() => props.months || []);
const totalsData = computed(() => props.totals || []);

// Chart reference
const chartRef = ref(null);

onMounted(() => {
  if (chartRef.value) {
    const options = {
      series: [
        {
          name: 'Monthly Visits',
          data: totalsData.value
        }
      ],
      chart: {
        height: 350,
        type: 'area',
      },
      dataLabels: {
        enabled: true
      },
      stroke: {
        curve: 'smooth'
      },
      xaxis: {
        categories: monthsData.value
      },
      tooltip: {
        x: {
          format: 'MMMM'
        }
      },
      title: {
        text: 'Gym Visits',
        align: 'left'
      }
    };

    const chart = new ApexCharts(chartRef.value, options);
    chart.render();
  }
});
</script>

<template>
  <div class="py-4">
    <div class="bg-white shadow-md rounded-lg p-4">
      <h2 class="text-xl font-semibold mb-4">Monthly Gym Visits</h2>
      <div ref="chartRef" class="w-full"></div>
    </div>
  </div>
</template>
