<template>
    <div>
        <div v-if="true">
            <!-- Projects Listing -->
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Issues
                </template>

                <template #description>
                    Issues that belongs to this project
                </template>

                <!-- List -->
                <template #content>
                    <div class="space-y-6">
                        <div v-if="issues.length > 0">
                            <div class="flex items-center justify-between" v-for="issue in issues" :key="issue.id">
                                <div class="text-gray-600">{{ issue.jira_key }}</div>
                                <div class="text-gray-600">{{ issue.summary }}</div>
                            </div>
                        </div>
                        <div v-else>
                            <div class="flex items-center justify-between">
                                We have no issue for this project at the moment.
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
            JetActionSection
        },

        props: [
            'issues'
        ],

        data() {
            return {
            }
        },

        methods: {
        },
    }
</script>
