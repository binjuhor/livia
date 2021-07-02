<!--suppress NpmUsedModulesInstalled, JSUnresolvedVariable -->
<template>
    <div>
        <div v-if="true">
            <!-- Projects Listing -->
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Invoices
                </template>

                <template #description>
                    Invoices that belongs to this project
                </template>

                <!-- List -->
                <template #content>
                    <div class="space-y-6">
                        <div v-if="invoices.length > 0">
                            <div class="flex items-center justify-between" v-for="invoice in invoices"
                                 :key="invoice.id">
                                <div class="text-gray-600">{{ invoice.reference }}</div>
                                <div class="text-gray-600">{{ invoice.xero_id }}</div>
                                <div class="text-gray-600">{{ invoice.total }}</div>
                            </div>
                        </div>
                        <div v-else>
                            <div class="flex items-center justify-between">
                                We have no invoice for this project at the moment.
                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>
        </div>
    </div>
</template>

<script>
import JetActionSection from '@/Jetstream/ActionSection'

export default {
    components: {
        JetActionSection,
    },

    props: [
        'invoices'
    ],

    data() {
        return {}
    },

    methods: {
    },
}
</script>
