import http from "../http-request";

const API_URL = '/riesgo_automotor';
const API_URL_RA = '/riesgo_automotor';

const state = () => ({
    riesgo_automotor: {
        tipo_patente: 0
    },
    riesgo_automotores: [],
    otro_riesgo: {},
    loading: false
});
const mutations = {
    SET_RIESGO_AUTOMOTORES(state, riesgo_automotores) {
        state.loading = false
        state.riesgo_automotores = riesgo_automotores;
    },
    SET_RIESGO_AUTOMOTOR(state, riesgo_automotor) {
        state.riesgo_automotor = riesgo_automotor;
    },
    RESET_RIESGO_AUTOMOTOR(state) {
        state.riesgo_automotor = Object.assign({}, { tipo_patente: 0 });
    },
    UPDATE_RIESGO_AUTOMOTOR(state, riesgo_automotor) {
        const item = state.riesgo_automotores.find(item => item.id === riesgo_automotor.id);
        Object.assign(item, riesgo_automotor);
    },
    UPDATE_KV(state, pair){
        state.riesgo_automotor = {...state.riesgo_automotor, ...pair};
    },
    CREATE_RIESGO_AUTOMOTOR(state, riesgo_automotor) {
        state.riesgo_automotores.push(riesgo_automotor);
    },
    DELETE_RIESGO_AUTOMOTOR(state, id) {
        state.riesgo_automotores = state.riesgo_automotores.filter(u => u.id != id);
    }
};
const actions = {
    async getRiesgoAutomotores({ commit }) {
        const resp = await http.get(API_URL_RA);
        commit("SET_RIESGO_AUTOMOTORES", resp.data);
    },

    async getRiesgoAutomotor({ commit }) {
        // const resp = await http.getOne(API_URL_RA, id);
        // commit("SET_RIESGO_AUTOMOTOR", resp.data);
    },

    async updateRiesgoAutomotor({ commit }, riesgo_automotor) {
        const resp = await http.put(
            API_URL_RA,
            riesgo_automotor.id,
            riesgo_automotor
        );
        if (resp.status === 200) {
            commit("UPDATE_RIESGO_AUTOMOTOR", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Riesgo editado con éxito!"
                },
                { root: true }
            );
            return true;
        } else {
            commit(
                "snackbar/SHOW_SNACK"({
                    color: "success",
                    snackText: "Algo salió mal..."
                }),
                { root: true }
            );
        }
    },

    async createRiesgoAutomotor({ commit }, riesgo_automotor) {
        const resp = await http.post(API_URL_RA, riesgo_automotor);
        if (resp.status === 201) {
            commit("CREATE_RIESGO_AUTOMOTOR", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Riesgo creado con éxito!"
                },
                { root: true }
            );
            return true;
        } else {
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "red",
                    snackText: "Algo salió mal, intente nuevamente..."
                },
                { root: true }
            );
        }
    },

    async deleteRiesgoAutomotor({ commit }, id) {
        const resp = await http.delete(API_URL_RA, id);
        if (resp.status === 200) {
            commit("DELETE_RIESGO_AUTOMOTOR", id);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Riesgo eliminado con éxito!"
                },
                { root: true }
            );
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
