import {
    Calendar,
    ChartColumnBig,
    CircleUser,
    Contact,
    FileText,
    Mail,
    MessageCircle,
    NotebookPen,
    Rss,
    ShoppingBag,
    ShoppingCart,
    Sparkles,
    SquareKanban,
    Target,
} from '@lucide/vue';
import { dashboard } from '@/routes';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { NavGroup, NavItem } from '@/types';

/**
 * Single source of truth for the application navigation, shared by the vertical
 * (sidebar) and horizontal (header) layouts.
 */
export const navGroups: NavGroup[] = [
    {
        title: 'Dashboard',
        items: [
            { title: 'Analytics', href: dashboard(), icon: ChartColumnBig },
            { title: 'eCommerce', href: '#', icon: ShoppingBag },
            { title: 'CRM Dashboard', href: '#', icon: Target },
        ],
    },
    {
        title: 'Apps',
        items: [
            {
                title: 'AI',
                href: '#',
                icon: Sparkles,
                items: [
                    { title: 'Assistant', href: '#' },
                    { title: 'Playground', href: '#' },
                ],
            },
            { title: 'Calendar', href: '#', icon: Calendar },
            { title: 'Chats', href: '#', icon: MessageCircle },
            { title: 'Email', href: '#', icon: Mail },
            { title: 'Notes', href: '#', icon: NotebookPen },
            { title: 'Contacts', href: '#', icon: Contact },
            {
                title: 'Invoice',
                href: '#',
                icon: FileText,
                items: [
                    { title: 'List', href: '#' },
                    { title: 'Detail', href: '#' },
                    { title: 'Create', href: '#' },
                ],
            },
            {
                title: 'User Profile',
                href: '#',
                icon: CircleUser,
                items: [
                    { title: 'Profile', href: editProfile() },
                    { title: 'Security', href: editSecurity() },
                ],
            },
            {
                title: 'Blogs',
                href: '#',
                icon: Rss,
                items: [
                    { title: 'Posts', href: '#' },
                    { title: 'Detail', href: '#' },
                ],
            },
            {
                title: 'Ecommerce',
                href: '#',
                icon: ShoppingCart,
                items: [
                    { title: 'Shop', href: '#' },
                    { title: 'Checkout', href: '#' },
                ],
            },
            { title: 'Kanban', href: '#', icon: SquareKanban },
        ],
    },
];

/**
 * Flattened entries that point at a real route, for the horizontal layout.
 */
export const topLevelNavItems: NavItem[] = navGroups
    .flatMap((group) => group.items)
    .filter((item) => item.href !== '#');
