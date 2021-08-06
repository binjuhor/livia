<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{project.name}}
                </h2>

                <div>
                    <jet-secondary-button class="ml-2" @click="syncProject" :class="{ 'opacity-25': syncProjectForm.processing }"
                                :disabled="syncProjectForm.processing">
                        Sync Issues
                    </jet-secondary-button>

                    <jet-button class="ml-2" @click="createInvoice" :class="{ 'opacity-25': createInvoiceForm.processing }"
                                :disabled="createInvoiceForm.processing">
                        Create Invoice
                    </jet-button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <issue-list :issues="issues" />

                <invoice-list class="mt-10"
                              :invoices="project.invoices"
                />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import IssueList from './IssueList'
    import InvoiceList from './InvoiceList'
    import JetButton from '@/Jetstream/Button'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    export default {
        props: [
            'project',
            'issues'
        ],

        data() {
            // noinspection JSUnresolvedFunction
            return {
                createInvoiceForm: this.$inertia.form({}),
                syncProjectForm: this.$inertia.form({
                    jira_key: this.project.jira_key
                }),
            }
        },

        components: {
            AppLayout,
            IssueList,
            InvoiceList,
            JetButton,
            JetSecondaryButton
        },

        methods: {
            createInvoice() {
                this.createInvoiceForm.post(route('projects.createInvoice', this.project), {
                    errorBag: 'createInvoice',
                    preserveScroll: true,
                    onSuccess: () => this.createInvoiceForm.reset(),
                });
            },

            syncProject() {
                this.syncProjectForm.post(route('projects.sync.jira'), {
                    errorBag: 'syncJiraProject',
                    preserveScroll: true,
                    onSuccess: () => this.syncJiraProjectForm.reset(),
                });
            }
        }
    }
</script>
