import http from "../http-request";

const API_URL = '/administracion/companias';

const state = () => ({
    companias: [],
    compania: {
        cuit: "",
        activo: true,
        cuitOriginal:''
    }
});
const mutations = {
    SET_COMPANIAS(state, companias) {
        state.companias = companias
    },
    SET_COMPANIA(state, compania) {
        state.compania = compania
        state.compania.cuitOriginal = compania.cuit
    },
    RESET_COMPANIA(state) {
        state.compania = Object.assign(
            {},
            {
                cuit: "",
                activo: true,
                cuitOriginal:''
            }
        );
    },
    CREATE_COMPANIA(state, compania) {
        state.companias.push(compania);
    },
    UPDATE_COMPANIA(state, compania) {
        const item = state.companias.find(item => item.id === compania.id);
        Object.assign(item, compania);
    },
    DELETE_COMPANIA(state, id) {
        state.companias = state.companias.filter(c => c.id != id);
    }
};
const actions = {
    async getCompanias({ commit }) {
        const resp = await http.load(API_URL);
        commit('SET_COMPANIAS', resp.data)
    },
    async getCompania({ commit }, nombre) {
        const resp = await http.loadOne(API_URL, nombre);
        commit('SET_COMPANIA', resp.data)
    },
    async createCompania({ commit }, compania) {
        const resp = await http.create(API_URL, compania)
        if (resp.status === 201) {
            commit("CREATE_COMPANIA", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {   snackbar: true,
                    color: "success",
                    snackText: "Compania creado con éxito!"
                },
                { root: true }
            );
            return true;
        } else {
            commit(
                "snackbar/SHOW_SNACK",
                {   snackbar: true,
                    color: "red",
                    snackText: "Algo salió mal, intente nuevamente..."
                },
                { root: true }
            );
        }
    },
    async updateCompania({ commit }, compania) {
        const resp = await http.update(
            API_URL,
            compania.id,
            compania
        );
        if (resp.status === 200) {
            commit("UPDATE_COMPANIA", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "success",
                    snackText: "Compania editada con éxito!"
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
    async deleteCompania({ commit }, id) {
        const resp = await http.delete(API_URL, id);
        if (resp.status === 200) {
            commit("DELETE_COMPANIA", id);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    snackbar: true,
                    color: "success",
                    snackText: "Compania eliminada con éxito!"
                },
                { root: true }
            );
            commit('modal/HIDE_MODAL', false,
                { root: true });
        } else {
            commit(
                "snackbar/SHOW_SNACK"({
                    snackbar: true,
                    color: "success",
                    snackText: "Algo salió mal..."
                }),
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
