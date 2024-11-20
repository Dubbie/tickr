import { defineStore } from 'pinia';

export const useTicketStore = defineStore('ticket', {
    state: () => ({
        tickets: [],
        loading: false,
        tabOptions: [
            {
                label: 'Open tickets',
                name: 'open',
                badge: {
                    label: 0,
                    color: 'indigo',
                },
            },
            {
                label: 'Archived',
                name: 'archived',
                badge: {
                    label: 0,
                    color: 'teal',
                },
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
        ticketCounts: {
            open: 0,
            archived: 0,
        },
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

        async fetchTicketCounts() {
            try {
                const response = await axios.get(route('api.ticket.counts'));

                this.ticketCounts.open = response.data.open || 0;
                this.ticketCounts.archived = response.data.archived || 0;
            } catch (err) {
                console.error('Error fetching ticket counts!', err);
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
        updatedTabOptions: (state) => {
            return state.tabOptions.map((tab) => ({
                ...tab,
                badge: {
                    ...tab.badge,
                    label: state.ticketCounts[tab.name] || 0,
                },
            }));
        },
    },
});
