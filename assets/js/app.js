import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import CssBaseline from '@material-ui/core/CssBaseline';
import { ThemeProvider } from '@material-ui/core/styles';
import store from '@store/main';
import { theme } from '@theme/makerpizza';

const render = () => {
    const App = require('@components/App').default;

    ReactDOM.render(
        <Provider store={store}>
            <ThemeProvider theme={theme}>
                <CssBaseline />
                <App />
            </ThemeProvider>
        </Provider>,
        document.getElementById('root')
    );
};
render();
