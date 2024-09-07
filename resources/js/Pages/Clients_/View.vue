<template>
   
   <Layout>

      <p>
         
         {{ client.id }}
         {{ client.first_name }}

      </p>

   </Layout>

</template>

<script setup>

import Layout from '@/Layouts/Layout.vue';

const props = defineProps({
   client: Object
 });



</script>