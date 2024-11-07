<template>

   <div v-if="links.length > 0">

      <div class="flex flex-wrap mb-1">
         <template v-for="(link, linkIndex) in links" :key="linkIndex">
            <div
               v-if="link.url === null"
               class="mr-1 mb-1 px-3 py-2 text-sm leading-4 text-gray-500 border rounded"
               v-html="link.label"
            >

            </div>

            <Link
               v-else
               class="mr-1 mb-1 px-3 py-2 text-sm leading-4 border rounded hover:bg-white "
               :class="{ 'bg-red-600 text-white': link.active }"
               :href="link.url"
            >
               <span v-html="link.label"></span>
            </Link>

         </template>

      </div>
   </div>

</template>

<script setup>
import { Link } from '@inertiajs/vue3';

   defineProps({
      links: Array
   })
</script>