import Home from '../components/Home';
import Books from '../components/Books';
import Readers from '../components/Readers';
import NotFound from '../components/NotFound';

export default [
    {
        exact: true,
        path: '/',
        component: Home
    },
    {
        path: '/books',
        component: Books
    },
    {
        path: '/readers',
        component: Readers
    },
    {
        component: NotFound
    }
];
