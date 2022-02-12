import http from "../http-request";

const API_URL = '/configuracion/organizadores';

const state = () => ({
    organizadores: [],
    organizador: {
        cuit: "",
        matricula: "",
        activo: 1,
        matriuclaOriginal: '',
        cuitOriginal: ''
    },
    loading: false
});
const mutations = {
    SET_ORGANIZADORES(state, organizadores) {
        state.organizadores = organizadores;
        state.loading = false
    },
    SET_ORGANIZADOR(state, organizador) {
        state.organizador = organizador;
        state.organizador.cuitOriginal = organizador.cuit;
        state.organizador.matriculaOriginal = organizador.matricula;
    },
    RESET_ORGANIZADOR(state) {
        state.organizador = Object.assign(
            {},
            {
                cuit: "",
                matricula: "",
                activo: 1,
                matriculaOriginal: '',
                cuitOriginal: ''
            }
        );
    },
    UPDATE_ORGANIZADOR(state, organizador) {
        const item = state.organizadores.find(item => item.id === organizador.id);
        Object.assign(item, organizador);
    },
    CREATE_ORGANIZADOR(state, organizador) {
        state.organizadores.unshift(organizador);
    },
    DELETE_ORGANIZADOR(state, id) {
        state.organizadores = state.organizadores.filter(u => u.id != id);
    }
};
const actions = {
    async getOrganizadores({ commit }) {
        const resp = await http.get(API_URL);
        commit("SET_ORGANIZADORES", resp.data);
    },

    async getOrganizador({ commit }, id) {
        const resp = await http.getOne(API_URL, id);
        commit("SET_ORGANIZADOR", resp.data);
    },

    async updateOrganizador({ commit }, organizador) {
        const resp = await http.put(
            API_URL,
            organizador.id,
            organizador
        );
        if (resp.status === 200) {
            commit("UPDATE_ORGANIZADOR", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Organizador editado con éxito!"
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

    async createOrganizador({ commit }, organizador) {
        const resp = await http.post(API_URL, organizador);
        if (resp.status === 201) {
            commit("CREATE_ORGANIZADOR", resp.data);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Organizador creado con éxito!"
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

    async deleteOrganizador({ commit }, id) {
        const resp = await http.delete(API_URL, id);
        if (resp.status === 200) {
            commit("DELETE_ORGANIZADOR", id);
            commit(
                "snackbar/SHOW_SNACK",
                {
                    color: "success",
                    snackText: "Organizador eliminado con éxito!"
                },
                { root: true }
            );
        } else if (resp.status === 202) {
            commit(
                "snackbar/SHOW_SNACK", {
                color: "red",
                snackText: "Existen Códigos Organizadores relacionados a este Organizador"
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
