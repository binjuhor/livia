<template>
    <div>
        <div v-if="true">
            <jet-section-border />

            <!-- Import Project From Jira -->
            <jet-form-section @submitted="syncJiraProject">
                <template #title>
                    Import Project From Jira
                </template>

                <template #description>
                    Import data from Jira like projects and issues.
                </template>

                <!-- Import Project Form -->
                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            Please provide the key of the Jira project that you want to sync. For example: CB.
                        </div>
                    </div>

                    <!-- Jira Project Code -->
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="jira_key" value="Jira project key" />
                        <jet-input id="jira_key" type="text" class="mt-1 block w-full" v-model="syncJiraProjectForm.jira_key" />
                        <jet-input-error :message="syncJiraProjectForm.errors.jira_key" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="syncJiraProjectForm.recentlySuccessful" class="mr-3">
                        Sync successfully.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': syncJiraProjectForm.processing }" :disabled="syncJiraProjectForm.processing">
                        Sync
                    </jet-button>
                </template>
            </jet-form-section>
        </div>
    </div>
</template>

<script>
import JetFormSection from '@/Jetstream/FormSection'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import JetLabel from '@/Jetstream/Label'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetButton from '@/Jetstream/Button'
import JetActionMessage from '@/Jetstream/ActionMessage'
export default {
    components: {
        JetFormSection,
        JetSectionBorder,
        JetLabel,
        JetInput,
        JetInputError,
        JetButton,
        JetActionMessage
    },
    data() {
        return {
            syncJiraProjectForm: this.$inertia.form({
                jira_key: '',
            }),
        }
    },
    methods: {
        syncJiraProject() {
            this.syncJiraProjectForm.post(route('projects.sync.jira'), {
                errorBag: 'syncJiraProject',
                preserveScroll: true,
                onSuccess: () => this.syncJiraProjectForm.reset(),
            });
        }
    },
}
</script>