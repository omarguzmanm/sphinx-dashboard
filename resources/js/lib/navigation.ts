import {
    Calendar,
    ChartColumnBig,
    CircleUser,
    Contact,
    FileText,
    LayoutDashboard,
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
import type { LucideIcon } from '@lucide/vue';
import { toUrl } from '@/lib/utils';
import { dashboard, overview } from '@/routes';
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
            { title: 'Overview', href: overview(), icon: LayoutDashboard },
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

export type NavEntry = {
    title: string;
    icon?: LucideIcon;
    group: string;
    parent?: string;
};

/**
 * Resolve a URL back to its navigation entry, so anything that only knows
 * where a link points (the overview shortcuts) can still render the same
 * icon and label as the sidebar. Children inherit their parent's icon.
 */
export function findNavEntry(url: string): NavEntry | undefined {
    for (const group of navGroups) {
        for (const item of group.items) {
            if (toUrl(item.href) === url) {
                return {
                    title: item.title,
                    icon: item.icon,
                    group: group.title,
                };
            }

            for (const child of item.items ?? []) {
                if (toUrl(child.href) === url) {
                    return {
                        title: child.title,
                        icon: item.icon,
                        group: group.title,
                        parent: item.title,
                    };
                }
            }
        }
    }

    return undefined;
}
