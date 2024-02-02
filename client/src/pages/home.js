import AppLayout from '@/components/Layouts/AppLayout'
import axios from 'axios'
import Head from 'next/head'
import { useEffect, useState } from 'react'
import { Swiper, SwiperSlide } from 'swiper/react'
import 'swiper/css'
import { CardMedia, Typography } from '@mui/material'
import Link from 'next/link'

const Dashboard = () => {
    const [movies, setMovies] = useState([])

    useEffect(() => {
        const fetchMovies = async () => {
            try {
                const response = await axios.get('api/getPopularMovies')
                setMovies(response.data.results)
            } catch (err) {
                console.log(err)
            }
        }
        fetchMovies()
    }, [])

    return (
        <AppLayout
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Home
                </h2>
            }>
            <Head>
                <title>Laravel - Home</title>
            </Head>

            <Swiper
                spaceBetween={30}
                slidesPerView={5}
                onSlideChange={() => console.log('slide change')}
                onSwiper={swiper => console.log(swiper)}
                breakpoints={{
                    320: {
                        spaceBetween: 10,
                        slidesPerView: 1,
                    },
                    480: {
                        spaceBetween: 20,
                        slidesPerView: 3,
                    },
                    640: {
                        spaceBetween: 30,
                        slidesPerView: 4,
                    },
                    768: {
                        spaceBetween: 40,
                        slidesPerView: 5,
                    },
                }}>
                {movies.map(movie => (
                    <SwiperSlide key={movie.id}>
                        <Link href={`detail/movie/${movie.id}`}>
                            <CardMedia
                                component={'img'}
                                sx={{
                                    aspectRatio: '2/3',
                                }}
                                image={`https://image.tmdb.org/t/p/original${movie.poster_path}`}
                            />
                        </Link>

                        <Typography>公開日:{movie.release_date}</Typography>
                    </SwiperSlide>
                ))}
            </Swiper>
        </AppLayout>
    )
}

export default Dashboard
