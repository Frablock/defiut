import React, { useState, useEffect } from 'react';
import axios from 'axios';

function Browser() {
    const [loading, setLoading] = useState(false);
    const [lastId, setLastId] = useState(0);
    const [posts, setPosts] = useState([]);

    useEffect(() => {
        loadMore(lastId);

        const handleScroll = () => {
            if (window.innerHeight + document.documentElement.scrollTop >= document.documentElement.scrollHeight - 500 * 3) {
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
            setPosts([...posts, ...response.data]);
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
                    <div key={index} className="post">
                        <h2>{post.nom}</h2>
                        <p>{post.description}</p>
                        <p>Reward Points: {post.pointsRecompense}</p>
                        <p>Difficulty: {post.difficulte}</p>
                        <p>User: {post.user}</p>
                        <p>Tags: {post.tags.join(', ')}</p>
                    </div>
                ))}
            </div>
            {loading && <div className="ajax-load">Chargement...</div>}
        </>
    );
}

export default Browser;
