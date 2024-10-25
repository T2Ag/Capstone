<template>
   <Layout>
      <div>
         <h1 class="">Hello</h1>
         <ClientsChart :months="months" :totals="totals" />
      </div>
   </Layout>
</template>

<script setup>
import Layout from '../Layouts/Layout.vue';
import ClientsChart from '../Components/Charts/ClientsChart.vue';

const props = defineProps({
   months: Array,
   totals: Array,
});

console.log('Props in Home.vue:', props);
</script>