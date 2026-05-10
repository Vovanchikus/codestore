import type { Metadata } from "next";
import { Onest, Roboto_Condensed } from "next/font/google";
import "@/styles/global.scss";
import "@/styles/variables.scss";

const onest = Onest({
  variable: "--font-onest",
  subsets: ["cyrillic", "latin"],
  weight: ["100", "200", "300", "400", "500", "600", "700"],
  fallback: ["Arial", "sans-serif"],
});

export const metadata: Metadata = {
  title: "Панель администратора - CodeStore",
  description: "Панель администратора реализованная для сайта CodeStore",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="ru" className={`${onest.variable}`}>
      <body>{children}</body>
    </html>
  );
}
