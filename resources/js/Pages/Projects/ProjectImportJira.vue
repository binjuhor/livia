<template>
    <div>
        <div v-if="true">
            <jet-section-border />

            <!-- Import Project From Jira -->
            <jet-form-section @submitted="importJiraProject">
                <template #title>
                    Import Project From Jira
                </template>

                <template #description>
                    Create a new project based on Jira Projects.
                </template>

                <!-- Import Project Form -->
                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            Please provide the key of the Jira project that you want to add. For example: CB.
                        </div>
                    </div>

                    <!-- Jira Project Code -->
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="jira_key" value="Jira project key" />
                        <jet-input id="jira_key" type="text" class="mt-1 block w-full" v-model="addJiraProjectForm.jira_key" />
                        <jet-input-error :message="addJiraProjectForm.errors.jira_key" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="addJiraProjectForm.recentlySuccessful" class="mr-3">
                        Imported.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': addJiraProjectForm.processing }" :disabled="addJiraProjectForm.processing">
                        Import
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
                addJiraProjectForm: this.$inertia.form({
                    jira_key: '',
                }),
            }
        },

        methods: {
            importJiraProject() {
                this.addJiraProjectForm.post(route('projects.import.jira'), {
                    errorBag: 'addJiraProject',
                    preserveScroll: true,
                    onSuccess: () => this.addJiraProjectForm.reset(),
                });
            }
        },
    }
</script>
