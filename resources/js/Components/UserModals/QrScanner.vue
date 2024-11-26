<template>
   <div class="qr-scanner-container">
     <div id="reader" class="w-full max-w-md mx-auto h-64"></div>
     
     <div v-if="scanResult" class="mt-4 p-4 bg-gray-100 rounded">
       <h3 class="font-bold mb-2">Scanned Result:</h3>
       <pre class="break-words">{{ scanResult }}</pre>
     </div>
   </div>
 </template>
 
 <script setup>
 import { ref, onMounted, onUnmounted } from 'vue'
 import { Html5Qrcode } from 'html5-qrcode'
 
 // Reactive variable to store scan result
 const scanResult = ref(null)
 let html5QrCode = null
 
 // Initialize scanner function
 const initializeScanner = () => {
   // Create new scanner instance
   html5QrCode = new Html5Qrcode('reader')
   
   // Scanner configuration
   const config = { 
     fps: 10, 
     qrbox: { 
       width: 250, 
       height: 250 
     } 
   }
   
   // Start scanning
   html5QrCode.start(
     { facingMode: "environment" }, 
     config, 
     onScanSuccess
   ).catch(err => {
     console.error("Error starting QR scanner:", err)
   })
 }
 
 // Scan success handler
 const onScanSuccess = (decodedText, decodedResult) => {
   // Stop the scanner
   html5QrCode.pause(true)
   
   // Set the scanned result
   scanResult.value = decodedText
   
   // Optional: Restart scanning after a delay
   setTimeout(() => {
     html5QrCode.resume()
   }, 3000) // Wait 3 seconds before resuming
 }
 
 // Mount scanner when component is added to DOM
 onMounted(() => {
   initializeScanner()
 })
 
 // Cleanup when component is unmounted
 onUnmounted(() => {
   if (html5QrCode) {
     html5QrCode.stop().catch(err => {
       console.error("Error stopping QR scanner:", err)
     })
   }
 })
 </script>
 
 <style scoped>
 #reader {
   width: 100%;
   max-width: 500px;
   margin: 0 auto;
 }
 </style>