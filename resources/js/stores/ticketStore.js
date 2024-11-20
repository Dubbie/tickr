import { defineStore } from 'pinia';

export const useTicketStore = defineStore('ticket', {
    state: () => ({
        tickets: [],
        loading: false,
        tabOptions: [
            {
                label: 'Open tickets',
                name: 'open',
            },
            {
                label: 'Archived',
                name: 'archived',
            },
        ],
        tab: 'open', // 'open' or 'archived'
        form: {
            query: '',
            page: 1,
            perPage: 10,
        },
        lastPage: null,
        abortController: null,
        debounceTimeout: null,
    }),

    actions: {
        async fetchTickets() {
            if (this.abortController) {
                this.abortController.abort();
            }

            this.abortController = new AbortController();
            this.loading = true;

            try {
                const response = await axios.get(route('api.ticket.index'), {
                    params: {
                        ...this.form,
                        tab: this.tab,
                    },
                    signal: this.abortController.signal,
                });

                this.tickets = response.data.data;
                this.lastPage = response.data.last_page;
                this.form.page = response.data.current_page;
            } catch (err) {
                if (axios.isCancel(err)) {
                    console.log('Request canceled');
                } else {
                    console.error('Error while loading tickets!', err);
                }
            } finally {
                this.loading = false;
            }
        },

        setTab(tab) {
            this.tab = tab;
            this.form.page = 1; // Reset to the first page
            this.fetchTickets(); // Fetch tickets for the selected tab
        },
    },

    getters: {
        totalTickets: (state) => state.tickets.length,
    },
});
