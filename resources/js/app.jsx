import React from 'react';
import ReactDOM from 'react-dom';
import Header from './components/react/Header';
import Footer from './components/react/Footer';
import UrlShortenerForm from './components/react/UrlShortener/UrlShortenerForm';
import { LinkIcon } from '@heroicons/react/24/solid';
import '../css/app.css';

const App = () => {
    return (
        <div className="flex flex-col min-h-screen">
            <Header />
            <main className="flex-grow">
                <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
                    <div className="sm:mx-auto sm:w-full sm:max-w-sm">
                        <LinkIcon className="h-12 w-12 mx-auto text-gray-900" />
                        <h2 className="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
                            Ingresa la url a acortar
                        </h2>
                    </div>

                    <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                        <UrlShortenerForm />
                    </div>
                </div>
            </main>
            <Footer />
        </div>
    );
};

ReactDOM.render(<App />, document.getElementById('app'));