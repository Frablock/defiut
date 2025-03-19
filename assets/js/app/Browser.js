import React, { useState, useEffect } from 'react';
import axios from 'axios';
import DefiBox from './DefiBox';

function Browser(props) {
    const [loading, setLoading] = useState(false);
    const [lastId, setLastId] = useState(0);
    const [posts, setPosts] = useState([]);


    useEffect(() => {
        loadMore(lastId);
    }, []);

    useEffect(() => {

        const handleScroll = () => {
            if (document.documentElement.scrollTop >= document.documentElement.scrollHeight - 100 *6) {
                loadMore(lastId);
            }
        };

        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, [lastId]);

    const loadMore = async (lastId) => {
        setLoading(true);
        try {
            const response = await axios.get(`api/defis?id=${lastId}`);
            setPosts([...response.data]);
            setLastId(lastId + 6+1);
        } catch (error) {
            alert('Le serveur ne répond pas...');
        } finally {
            setLoading(false);
        }
    };

    return (
        <>
            <h1>Browser</h1>
            <div id="post-data">
                {posts.map((post, index) => (
                    <DefiBox {...props} data={post}/>
                ))}
            </div>
            {loading && <div className="ajax-load">Chargement...</div>}
        </>
    );
}

export default Browser;
