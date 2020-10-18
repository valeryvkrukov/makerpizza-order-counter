import {combineReducers, configureStore} from '@reduxjs/toolkit';
import account, { logout, loginSucceed } from '@store/reducers/account';

const appReducer = combineReducers({
    account
});

const rootReducer = (state, action) => {
    if (action.type === loginSucceed.type) {
        return appReducer(undefined, action);
    }
    if (action.type === logout.type) {
        return appReducer(undefined, action);
    }
    return appReducer(state, action);
};

const store = configureStore({
    reducer: rootReducer,
});

export default store;
