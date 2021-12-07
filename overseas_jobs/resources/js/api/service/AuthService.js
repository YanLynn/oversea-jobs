import { api } from '../apiBasePath';

export const login = ( login ) => {
    return api.post("/login", login);
}