<script setup>
import { ref, onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
import ApexCharts from 'apexcharts';

// Define props
const props = defineProps({
  totalWalkInClients: {
    type: Number,
    default: 0
  },
  totalMonthlyClients: {
    type: Number,
    default: 0
  }
});

// Initialize Flowbite
onMounted(() => {
  initFlowbite();
});

// Chart reference
const chartRef = ref(null);

const getChartOptions = () => {
  // Calculate total clients
  const totalClients = props.totalWalkInClients + props.totalMonthlyClients;

  // Calculate percentages
  const walkInPercentage = totalClients > 0 
    ? ((props.totalWalkInClients / totalClients) * 100).toFixed(1)
    : 0;
  const monthlyPercentage = totalClients > 0 
    ? ((props.totalMonthlyClients / totalClients) * 100).toFixed(1)
    : 0;

  return {
    series: [
      props.totalWalkInClients, 
      props.totalMonthlyClients
    ],
    colors: ["#1C64F2", "#16BDCA"],
    chart: {
      height: 420,
      width: "100%",
      type: "pie",
    },
    stroke: {
      colors: ["white"],
    },
    plotOptions: {
      pie: {
        dataLabels: {
          offset: -25
        },
      },
    },
    labels: [
      `Walk-in (${walkInPercentage}%)`, 
      `Monthly (${monthlyPercentage}%)`
    ],
    dataLabels: {
      enabled: true,
      style: {
        fontFamily: "Inter, sans-serif",
      },
      formatter: function (val, opts) {
        return opts.w.config.series[opts.seriesIndex];
      }
    },
    title: {
      text: 'Clients Overview',
      align: 'center',
      style: {
        fontSize: '18px',
        fontWeight: 'bold',
        color: '#333'
      },
    },
    legend: {
      position: "bottom",
      fontFamily: "Inter, sans-serif",
    },
    responsive: [
      {
        breakpoint: 480,
        options: {
          chart: {
            height: 320
          }
        }
      }
    ]
  };
};

onMounted(() => {
  if (chartRef.value) {
    const chart = new ApexCharts(chartRef.value, getChartOptions());
    chart.render();
  }
});
</script>

<template>
   <div class="flex justify-center bg-white shadow-md rounded-lg p-4">
      <div class=" flex justify-between">

         <div class="flex justify-center items-center mx-auto">

            <div ref="chartRef" class="w-[25rem]"></div>

         </div>

      </div>   

      <div class="">
            <h3 class=" text-lg font-semibold mb-4">Clients Summary</h3>
            <ul class="space-y-2 ">
            <li class="flex justify-between">
               <span>Total Clients:</span>
               <span class="font-bold">{{ props.totalWalkInClients + props.totalMonthlyClients }}</span>
            </li>
            <li class="flex justify-between">
               <span>Walk-in Clients:</span>
               <span class="font-bold">{{ props.totalWalkInClients }}</span>
            </li>
            <li class="flex justify-between">
               <span>Monthly Clients:</span>
               <span class="font-bold">{{ props.totalMonthlyClients }}</span>
            </li>
            </ul>
         </div>
   </div>
  

</template>
