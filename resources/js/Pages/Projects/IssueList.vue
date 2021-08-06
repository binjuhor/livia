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
                        <div class="grid grid-flow-col grid-cols-10">
                            <div class="text-gray-600">Jira Key</div>
                            <div class="text-gray-600 col-span-6">Summary</div>
                            <div class="text-gray-600">Points</div>
                            <div class="text-gray-600">Type</div>
                            <div class="text-gray-600">Status</div>
                        </div>
                        <div v-if="issues.data.length > 0">
                            <div class="grid grid-flow-col grid-cols-10" v-for="issue in issues.data" :key="issue.id">
                                <div class="text-gray-600">{{ issue.jira_key }}</div>
                                <div class="text-gray-600 col-span-6">{{ issue.summary }}</div>
                                <div class="text-gray-600">{{ issue.story_point }}</div>
                                <div class="text-gray-600">{{ typeLabel(issue.type) }}</div>
                                <div class="text-gray-600">{{ statusLabel(issue.status) }}</div>
                            </div>

                            <div class="flex flex-row-reverse mt-6">
                                <pagination :links="issues.links"/>
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
import Pagination from '../../Components/Pagination'

export default {
    components: {
        JetActionSection,
        Pagination
    },

    props: [
        'issues',
    ],

    data() {
        return {}
    },

    methods: {
        typeLabel(type) {
            return ['Story', 'Task', 'Bug'][type]
        },

        statusLabel(status) {
            return ['To Do', 'Working', 'Done'][status]
        },
    },
}
</script>
