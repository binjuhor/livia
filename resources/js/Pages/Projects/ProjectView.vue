<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                View Project {{project.name}}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <issue-list :issues="project.issues" />

                <invoice-list class="mt-10"
                              :invoices="project.invoices"
                />

                <button @click="createInvoice"
                              class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                >
                    Create an invoice
                </button>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import IssueList from '@/Pages/Projects/IssueList'
    import InvoiceList from '@/Pages/Projects/InvoiceList'

    export default {
        props: [
            'project'
        ],

        data() {
            // noinspection JSUnresolvedFunction
            return {
                createInvoiceForm: this.$inertia.form({}),
            }
        },

        components: {
            AppLayout,
            IssueList,
            InvoiceList
        },

        methods: {
            createInvoice() {
                this.createInvoiceForm.post(route('projects.createInvoice', this.project), {
                    errorBag: 'createInvoice',
                    preserveScroll: true,
                    onSuccess: () => this.createInvoiceForm.reset(),
                });
            }
        }
    }
</script>
