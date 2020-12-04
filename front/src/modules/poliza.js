import http from "../http-request";

const API_URL = '/polizas';

const state = () => ({
    polizas: [],
    poliza: {},
    loading: true
});
const mutations = {
    SET_POLIZAS(state, polizas) {
        state.loading = false
        state.polizas = polizas
    },
    SET_POLIZA(state, poliza) {
        state.poliza = poliza
        state.loading = false
    },
    RESET_POLIZA(state) {
        state.poliza = Object.assign(
            {},
            {}
        );
    },
    CREATE_POLIZA(state, poliza) {
        state.polizas.push(poliza);
    },
    UPDATE_POLIZA(state, poliza) {
        var item = state.polizas.find(item => item.id === poliza.id);
        return Object.assign(item, poliza);
    },
    DELETE_POLIZA(state, id) {
        state.polizas = state.polizas.filter(c => c.id != id);
    }
};
const actions = {
    async getPolizas({ commit }) {
        const resp = await http.get(API_URL);
        commit('SET_POLIZAS', resp.data)
    },
    async getPoliza({ commit, dispatch }, id) {
        const resp = await http.getOne(API_URL, id);
        commit('SET_POLIZA', resp.data)
    },
    async createPoliza({ commit }, poliza) {
        const resp = await http.post(API_URL, poliza)
        if (resp.status === 201) {
            commit("CREATE_POLIZA", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "success",
                    snackText: "Poliza creada con éxito!"
                },
                { root: true }
            );
            return true;
        } else {
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "red",
                    snackText: "Algo salió mal, intente nuevamente..."
                },
                { root: true }
            );
        }
    },
    async updatePoliza({ commit }, poliza) {
        const resp = await http.put(
            API_URL,
            poliza.id,
            poliza
        );
        if (resp.status === 200) {
            commit("UPDATE_POLIZA", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "success",
                    snackText: "Poliza editada con éxito!"
                },
                { root: true }
            );
            return true;
        } else {
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "red",
                    snackText: "Algo salió mal, intente nuevamente..."
                },
                { root: true }
            );
        }
    },
    async deletePoliza({ commit }, id) {
        const resp = await http.delete(API_URL, id);
        if (resp.status === 200) {
            commit("DELETE_POLIZA", id);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "success",
                    snackText: "Poliza eliminada con éxito!"
                },
                { root: true }
            );
            commit('modal/HIDE_MODAL', false,
                { root: true });
        } else {
            commit(
                "snackbar/SHOW_SNACK", {
                color: "success",
                snackText: "Algo salió mal..."
            },
                { root: true }
            );
        }
    }

};
export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters: {}
};
