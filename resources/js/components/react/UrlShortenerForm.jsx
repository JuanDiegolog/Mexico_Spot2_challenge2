import React, { useState } from 'react';
import axios from '../axios';
import { FaCopy } from 'react-icons/fa';
import { ClipLoader } from 'react-spinners';

const UrlShortenerForm = () => {
    const [url, setUrl] = useState('');
    const [error, setError] = useState('');
    const [shortenedUrl, setShortenedUrl] = useState('');
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');
        setShortenedUrl('');
        setLoading(true);

        const urlPattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i'); // fragment locator

        if (!urlPattern.test(url)) {
            setError('Por favor ingresa una URL válida.');
            setLoading(false);
            return;
        }

        try {
            const response = await axios.post('/api/v1/UrlShortener/shorten', { original_url: url });
            setShortenedUrl(response.data.shortened_url);
        } catch (error) {
            setError('Ocurrió un error al acortar la URL.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="p-4">
            <form onSubmit={handleSubmit} className="flex flex-col items-center">
                <input
                    type="text"
                    value={url}
                    onChange={(e) => setUrl(e.target.value)}
                    placeholder="Ingresa la URL"
                    className="p-2 border border-gray-300 rounded mb-2 w-full max-w-md"
                />
                <button type="submit" className="bg-blue-500 text-white p-2 rounded" disabled={loading}>
                    {loading ? 'Acortando...' : 'Acortar URL'}
                </button>
            </form>
            {loading && (
                <div className="flex justify-center mt-4">
                    <ClipLoader size={35} color={"#123abc"} loading={loading} />
                </div>
            )}
            {error && <p className="text-red-500 mt-2">{error}</p>}
            {shortenedUrl && (
                <div className="mt-4">
                    <p>URL Acortada: {shortenedUrl}</p>
                    <button
                        onClick={() => navigator.clipboard.writeText(shortenedUrl)}
                        className="bg-green-500 text-white p-2 rounded mt-2 flex items-center"
                    >
                        <FaCopy className="mr-2" />
                        Copiar
                    </button>
                </div>
            )}
        </div>
    );
};

export default UrlShortenerForm;