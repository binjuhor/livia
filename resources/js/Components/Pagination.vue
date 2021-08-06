<template>
    <div v-if="links.length > 3"
         class="bg-white px-4 flex items-center justify-between sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            <inertia-link :href="previousLink" preserve-scroll
                          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Previous
            </inertia-link>
            <inertia-link :href="nextLink" preserve-scroll
                          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Next
            </inertia-link>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->

                    <template v-for="(link, k) in links" :key="k">
                        <inertia-link v-if="isPrevious(link)" preserve-scroll
                                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                      :href="link.url ?? ''"
                        >
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </inertia-link>
                        <inertia-link v-else-if="isNext(link)" preserve-scroll
                                      :href="link.url ?? ''"
                                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </inertia-link>
                        <div v-else-if="isEmpty(link)" :href="link.url" preserve-scroll
                             class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                            {{ link.label }}
                        </div>
                        <inertia-link v-else-if="isActive(link)" :href="link.url ?? ''" aria-current="page" preserve-scroll
                                      class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                            {{ link.label }}
                        </inertia-link>
                        <inertia-link v-else-if="false === link.active" :href="link.url ?? ''" preserve-scroll
                                      class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                            {{ link.label }}
                        </inertia-link>
                    </template>
                </nav>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'Pagination',
    props: {
        links: Array,
    },
    methods: {
        isPrevious(link) {
            return -1 < link.label.indexOf('Previous')
        },
        isNext(link) {
            return -1 < link.label.indexOf('Next')
        },
        isEmpty(link) {
            return null === link.url && !(this.isPrevious(link) || this.isNext(link))
        },
        isActive(link) {
            return true === link.active
        }
    },
    computed: {
        previousLink() {
            return this.links.filter(link => this.isPrevious(link))[0].url ?? ''
        },
        nextLink() {
            return this.links.filter(link => this.isNext(link))[0].url ?? ''
        }
    }
}
</script>
<style scoped>
</style>