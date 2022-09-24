<template>
  <div>
        <span v-if="null === status || 0 === status"
              class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                        Draft
                                    </span>

    <span v-if="1 === status"
          class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">
                                        Sent
                                    </span>

    <span v-if="2 === status"
          class="bg-red-100 text-red-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">
                                        Paid
                                    </span>
  </div>
</template>

<script>
export default {
  props: ['status']
}
</script>