import http from "../http-request";

const API_URL = '/administracion/anios';

const state = () => ({
    anios: [],
    anio: {},
    
    loading: false
});
const mutations = {
    SET_ANIOS(state, anios) {
        state.loading = false
        state.anios = anios;
    },
    SET_ANIO(state, anio) {
        state.anio = anio;
        state.anio.nombreOriginal = anio.nombre;
    },
    RESET_ANIO(state) {
        state.anio = Object.assign({},{});
    },
    UPDATE_ANIO(state, anio) {
        const item = state.anios.find(item => item.id === anio.id);
        Object.assign(item, anio);
    },
    CREATE_ANIO(state, anio) {
        state.anios.push(anio);
    },
    DELETE_ANIO(state, id) {
        state.anios = state.anios.filter(u => u.id != id);
    }
};
const actions = {
    async getAnios({ state, commit }) {
        if(state.anios.length == 0){
            state.loading = true;
        }
        const resp = await http.get(API_URL);
        commit("SET_ANIOS", resp.data);
    },

    async getAnio({ commit }, id) {
        const resp = await http.getOne(API_URL, id);
        commit("SET_ANIO", resp.data);
    },

    async updateAnio({ commit }, anio) {
        const resp = await http.put(
            API_URL,
            anio.id,
            anio
        );
        if (resp.status === 200) {
            commit("UPDATE_ANIO", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Anio editada con éxito!"
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

    async createAnio({ commit }, anio) {
        const resp = await http.post(API_URL, anio);
        if (resp.status === 201) {
            commit("CREATE_ANIO", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Anio creada con éxito!"
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

    async deleteAnio({ commit }, id) {
        const resp = await http.delete(API_URL, id);
        if (resp.status === 200) {
            commit("DELETE_ANIO", id);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Anio eliminada con éxito!"
                },
                { root: true }
            );
        } else if (resp.status === 202) {
            commit(
                "snackbar/SHOW_SNACK", {
                color: "red",
                snackText: "Existen modelos relacionados a este anio"
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
