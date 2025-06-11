import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import ErrorPage from './errorPage/Error';
import Login from './login/Login';
import Browser from './app/Browser';

function RoutesDispatcher(props) {
  return (
    <>
      <Routes>
        <Route 
          path="/"
          element={props.isLoggedIn ? <Navigate to="/app" replace /> : <Navigate to="/login" replace />} 
        />
        <Route 
          path="/login"
          element={<Login {...props}/>}
        />
        <Route 
          path="/register"
          element={<Login {...props}/>}
        />
        <Route 
          path="/app"
          element={<Browser {...props}/>}
        />
        <Route 
          path="*"
          element={<ErrorPage {...props}/>}
        />
      </Routes>
    </>
  );
}

export default RoutesDispatcher;