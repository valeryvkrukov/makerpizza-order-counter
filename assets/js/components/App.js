import React from 'react';
import { BrowserRouter, Switch, Route } from 'react-router-dom';

import PageLoading from '@components/common/PageLoading';
import SignIn from '@components/auth/SignIn';

const App = () => (
    <React.Suspense fallback={<PageLoading/>}>
        <BrowserRouter>
            <Switch>
                <Route exact path="/" component={SignIn}/>
            </Switch>
        </BrowserRouter>
    </React.Suspense>
);

export default App;
