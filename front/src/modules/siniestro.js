import http from "../http-request";

const API_URL = '/siniestros';

const state = () => ({
    siniestros: [],
    siniestro: {},
    loading: true
});
const mutations = {
    SET_SINIESTROS(state, siniestros) {
        state.siniestros = siniestros
        state.loading = false
    },
    SET_SINIESTRO(state, siniestro) {
        state.siniestro = siniestro
        state.loading = false
    },
    RESET_SINIESTRO(state) {
        state.siniestro = Object.assign(
            {},
            {}
        );
    },
    CREATE_SINIESTRO(state, siniestro) {
        state.siniestros.push(siniestro);
    },
    UPDATE_SINIESTRO(state, siniestro) {
        var item = state.siniestros.find(item => item.id === siniestro.id);
        Object.assign(item, siniestro);
    },
    DELETE_SINIESTRO(state, id) {
        state.siniestros = state.siniestros.filter(c => c.id != id);
    },
};
const actions = {
    async getSiniestros({ commit }) {
        const resp = await http.get(API_URL);
        commit('SET_SINIESTROS', resp.data)
    },
    async getSiniestro({ commit }, id) {
        const resp = await http.getOne(API_URL, id);
        commit('SET_SINIESTRO', resp.data)
    },
    async getSiniestrosDePoliza({ commit }, id) {
        const resp = await http.getOne('/siniestros/indexFiltrado', id);
        commit('SET_SINIESTROS', resp.data)
    },
    async createSiniestro({ commit }, siniestro) {
        const resp = await http.post(API_URL, siniestro)
        if (resp.status === 201) {
            commit("CREATE_SINIESTRO", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "success",
                    snackText: "Siniestro creado con éxito!"
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
    async updateSiniestro({ commit }, siniestro) {
        const resp = await http.put(
            API_URL,
            siniestro.id,
            siniestro
        );
        if (resp.status === 200) {
            commit("UPDATE_SINIESTRO", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "success",
                    snackText: "Siniestro editado con éxito!"
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
    async deleteSiniestro({ commit }, id) {
        const resp = await http.delete(API_URL, id);
        if (resp.status === 200) {
            commit("DELETE_SINIESTRO", id);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "success",
                    snackText: "Siniestro eliminado con éxito!"
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
    },

};
export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters: {}
};
